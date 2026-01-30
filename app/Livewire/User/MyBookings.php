<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class MyBookings extends Component
{
    public function render()
    {
        $bookings = Booking::with('house')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.user.my-bookings', compact('bookings'))
            ->layout('layouts.app', ['title' => 'Мои бронирования']);
    }
}

