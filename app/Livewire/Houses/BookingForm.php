<?php

namespace App\Livewire\Houses;

use Livewire\Component;
use App\Models\House;
use App\Models\Booking;
use App\Models\ExtraService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BookingForm extends Component
{
    public House $house;
    public $start_date;
    public $end_date;
    public $guests_count = 2;
    public $payment_method = 'card';
    public $show_payment = false;
    public $total_amount = 0;
    public $calendar_month;
    public $calendar_year;
    public $calendar_days = [];
    public $extra_services = [];
    public $selected_services = [];
    public $extras_total = 0;

    protected $rules = [
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
        'guests_count' => 'required|integer|min:1',
        'payment_method' => 'required|in:card,qiwi,yandex',
    ];

    public function mount(House $house)
    {
        $this->house = $house;
        $this->guests_count = min(2, $house->max_guests);
        $this->calendar_month = now()->month;
        $this->calendar_year = now()->year;
        $this->generateCalendar();

        if (Schema::hasTable('extra_services')) {
            $this->extra_services = ExtraService::where('house_id', $this->house->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            $this->extra_services = [];
        }
    }

    public function updatedStartDate()
    {
        $this->calculateTotal();
    }

    public function updatedEndDate()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->extras_total = 0;

        if ($this->extra_services && $this->selected_services) {
            foreach ($this->extra_services as $service) {
                if (in_array($service->id, $this->selected_services)) {
                    $this->extras_total += $service->price;
                }
            }
        }

        $this->total_amount = 0;

        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);
            $nights = $start->diffInDays($end);
            $pricePerNight = $this->house->hasActiveDiscount() ? $this->house->final_price : $this->house->price_per_night;
            $this->total_amount = $nights * $pricePerNight + $this->extras_total;
        }
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->calendar_year, $this->calendar_month, 1)->subMonth();
        $this->calendar_year = $date->year;
        $this->calendar_month = $date->month;
        $this->generateCalendar();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->calendar_year, $this->calendar_month, 1)->addMonth();
        $this->calendar_year = $date->year;
        $this->calendar_month = $date->month;
        $this->generateCalendar();
    }

    public function selectDate(string $date)
    {
        $selected = Carbon::parse($date)->toDateString();

        if ($this->isDateUnavailable(Carbon::parse($selected))) {
            return;
        }

        if (!$this->start_date || ($this->start_date && $this->end_date)) {
            $this->start_date = $selected;
            $this->end_date = null;
        } elseif ($this->start_date && !$this->end_date) {
            if (Carbon::parse($selected)->gt(Carbon::parse($this->start_date))) {
                $this->end_date = $selected;
            } else {
                $this->start_date = $selected;
            }
        }

        $this->calculateTotal();
    }

    public function updatedSelectedServices()
    {
        $this->calculateTotal();
    }

    protected function generateCalendar()
    {
        $startOfMonth = Carbon::create($this->calendar_year, $this->calendar_month, 1)->startOfDay();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        $bookings = Booking::where('house_id', $this->house->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('start_date', '<=', $startOfMonth)
                            ->where('end_date', '>=', $endOfMonth);
                    });
            })
            ->get();

        $bookedDates = [];
        foreach ($bookings as $booking) {
            $current = $booking->start_date->copy();
            $lastDay = $booking->end_date->copy()->subDay();
            while ($current->lte($lastDay)) {
                $bookedDates[$current->toDateString()] = true;
                $current->addDay();
            }
        }

        $days = [];

        $firstDayOfWeek = $startOfMonth->dayOfWeekIso; // 1..7 (Mon..Sun)
        for ($i = 1; $i < $firstDayOfWeek; $i++) {
            $days[] = null;
        }

        $current = $startOfMonth->copy();
        while ($current->lte($endOfMonth)) {
            $dateString = $current->toDateString();
            $days[] = [
                'date' => $current->copy(),
                'is_available' => !isset($bookedDates[$dateString]),
                'is_today' => $current->isToday(),
            ];
            $current->addDay();
        }

        $this->calendar_days = $days;
    }

    protected function isDateUnavailable(Carbon $date): bool
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        return Booking::where('house_id', $this->house->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startOfDay, $endOfDay) {
                $query->whereBetween('start_date', [$startOfDay, $endOfDay])
                    ->orWhereBetween('end_date', [$startOfDay, $endOfDay])
                    ->orWhere(function ($q) use ($startOfDay, $endOfDay) {
                        $q->where('start_date', '<=', $startOfDay)
                            ->where('end_date', '>=', $endOfDay);
                    });
            })
            ->exists();
    }

    public function proceedToPayment()
    {
        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'guests_count' => 'required|integer|min:1',
        ]);

        if ($this->guests_count > $this->house->max_guests) {
            $this->addError('guests_count', 'Слишком много гостей для данного домика.');
            return;
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->calculateTotal();

        return redirect()->route('booking.payment', [
            'house' => $this->house->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'guests' => $this->guests_count,
            'total' => $this->total_amount,
            'extras' => implode(',', $this->selected_services),
        ]);
    }

    public function processPayment()
    {
        $this->validate();

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Проверка пересечения броней
        $conflicting = Booking::where('house_id', $this->house->id)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                      ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                      ->orWhere(function($q) {
                          $q->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                      });
            })
            ->exists();

        if ($conflicting) {
            $this->addError('start_date', 'Выбранные даты уже заняты. Пожалуйста, выберите другие даты.');
            return;
        }

        // Симуляция оплаты (заглушка)
        $paymentSuccess = true; // В реальной системе здесь будет вызов платежного API

        Booking::create([
            'user_id' => Auth::id(),
            'house_id' => $this->house->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'guests_count' => $this->guests_count,
            'status' => $paymentSuccess ? 'confirmed' : 'pending',
            'total_amount' => $this->total_amount,
            'payment_status' => $paymentSuccess ? 'paid' : 'pending',
            'payment_method' => $this->payment_method,
            'paid_at' => $paymentSuccess ? now() : null,
        ]);

        if ($paymentSuccess) {
            session()->flash('success', 'Бронирование успешно оплачено и подтверждено!');
        } else {
            session()->flash('success', 'Бронирование создано! Ожидается оплата.');
        }

        $this->reset(['start_date', 'end_date', 'show_payment', 'total_amount']);
        $this->guests_count = min(2, $this->house->max_guests);
    }

    public function cancelPayment()
    {
        $this->show_payment = false;
    }

    public function render()
    {
        return view('livewire.houses.booking-form');
    }
}
