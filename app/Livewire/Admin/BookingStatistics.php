<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingStatistics extends Component
{
    public $period = 'month'; // month, week, year
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->setPeriod();
    }

    public function setPeriod()
    {
        switch($this->period) {
            case 'week':
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            default:
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
    }

    public function updatedPeriod()
    {
        $this->setPeriod();
    }

    public function render()
    {
        $stats = $this->getStatistics();
        $dailyStats = $this->getDailyStatistics();
        $popularHouses = $this->getPopularHouses();

        return view('livewire.admin.booking-statistics', compact('stats', 'dailyStats', 'popularHouses'))
            ->layout('layouts.app', ['title' => 'Статистика продаж']);
    }

    private function getStatistics()
    {
        $query = Booking::whereBetween('created_at', [$this->startDate, $this->endDate]);

        return [
            'total_bookings' => (clone $query)->count(),
            'confirmed_bookings' => (clone $query)->where('status', 'confirmed')->count(),
            'total_revenue' => (clone $query)->where('payment_status', 'paid')->sum('total_amount') ?? 0,
            'pending_payments' => (clone $query)->where('payment_status', 'pending')->count(),
            'average_booking_value' => (clone $query)->where('payment_status', 'paid')->avg('total_amount') ?? 0,
        ];
    }

    private function getDailyStatistics()
    {
        return Booking::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getPopularHouses()
    {
        return Booking::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('payment_status', 'paid')
            ->with('house')
            ->select('house_id', DB::raw('COUNT(*) as bookings_count'), DB::raw('SUM(total_amount) as total_revenue'))
            ->groupBy('house_id')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'house' => $item->house,
                    'bookings_count' => $item->bookings_count,
                    'total_revenue' => $item->total_revenue,
                ];
            });
    }
}

