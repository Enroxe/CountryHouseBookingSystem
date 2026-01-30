<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\House;

class LandingPage extends Component
{
    public $currentSlide = 0;

    public function nextSlide()
    {
        $this->currentSlide = ($this->currentSlide + 1) % count($this->promotions());
    }

    public function prevSlide()
    {
        $this->currentSlide =
            ($this->currentSlide - 1 + count($this->promotions()))
            % count($this->promotions());
    }

    public function goToSlide(int $index)
    {
        $this->currentSlide = $index;
    }

    public function promotions(): array
    {
        return [
            [
                'title' => 'Скидка 20% на выходные',
                'description' => 'Забронируйте домик на выходные и получите скидку 20%',
                'image' => asset('sales.jpg'),
                'badge' => '-20%',
            ],
            [
                'title' => 'Популярные домики месяца',
                'description' => 'Самые бронируемые варианты',
                'image' => asset('houses/chale.jpg'),
                'badge' => 'Популярно',
            ],
            [
                'title' => 'Раннее бронирование',
                'description' => 'Специальные условия при раннем бронировании',
                'image' => asset('houses/lake-house.jpg'),
                'badge' => 'Акция',
            ],
        ];
    }

    public function render()
    {
        $popularHouses = House::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(3)
            ->get();

        return view('livewire.guest.landing-page', [
            'popularHouses' => $popularHouses,
            'promotions' => $this->promotions(),
        ]);
    }
}
