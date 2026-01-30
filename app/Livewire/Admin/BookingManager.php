<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Booking;

class BookingManager extends Component
{
    public function setStatus($id, $status)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $status]);
    }

    public function render()
    {
        $bookings = Booking::with(['user', 'house'])->latest()->get();

        return view('livewire.admin.booking-manager', compact('bookings'))
            ->layout('layouts.app', ['title' => 'Админ: Бронирования']);
    }
}
