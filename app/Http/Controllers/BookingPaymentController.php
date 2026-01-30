<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BookingPaymentController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'house' => 'required|exists:houses,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'guests' => 'required|integer|min:1',
        ]);

        $house = House::findOrFail($request->house);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $nights = $start->diffInDays($end);
        $pricePerNight = $house->hasActiveDiscount() ? $house->final_price : $house->price_per_night;
        $baseTotal = $nights * $pricePerNight;

        $extras = [];
        $extrasTotal = 0;

        if ($request->filled('extras') && Schema::hasTable('extra_services')) {
            $ids = collect(explode(',', $request->extras))
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            if (!empty($ids)) {
                $extras = \App\Models\ExtraService::where('house_id', $house->id)
                    ->whereIn('id', $ids)
                    ->where('is_active', true)
                    ->get();

                $extrasTotal = $extras->sum('price');
            }
        }

        $total = $baseTotal + $extrasTotal;

        return view('booking.payment', [
            'house' => $house,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'guests' => $request->guests,
            'total' => $total,
            'extras' => $extras,
            'extras_total' => $extrasTotal,
            'base_total' => $baseTotal,
        ]);
    }

    public function pay(Request $request)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'guests' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'card_number' => 'required|string|min:12|max:19',
            'card_holder' => 'required|string|min:3',
            'card_exp_month' => 'required|integer|min:1|max:12',
            'card_exp_year' => 'required|integer|min:' . now()->format('y'),
            'card_cvv' => 'required|string|min:3|max:4',
        ]);

        $house = House::findOrFail($request->house_id);

        // Проверка пересечения броней
        $conflicting = Booking::where('house_id', $house->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($conflicting) {
            return back()
                ->withErrors(['dates' => 'Выбранные даты уже заняты. Выберите другие даты.'])
                ->withInput();
        }

        // Здесь мог бы быть реальный вызов платёжного шлюза.
        $paymentSuccess = true;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'house_id' => $house->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'guests_count' => $request->guests,
            'status' => $paymentSuccess ? 'confirmed' : 'pending',
            'total_amount' => $request->total,
            'payment_status' => $paymentSuccess ? 'paid' : 'pending',
            'payment_method' => 'card',
            'paid_at' => $paymentSuccess ? now() : null,
        ]);

        return redirect()->route('booking.payment.success', $booking);
    }

    public function success(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        return view('booking.payment-success', compact('booking'));
    }
}

