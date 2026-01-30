<?php

namespace App\Livewire\Houses;

use Livewire\Component;
use App\Models\House;

class HouseDetails extends Component
{
    public House $house;

    public function mount(House $house)
    {
        $this->house = $house;
    }

    public function render()
    {
        return view('livewire.houses.house-details')
            ->layout('layouts.app', ['title' => $this->house->title]);
    }
}
