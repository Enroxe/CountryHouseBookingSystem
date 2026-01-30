<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\House;
use Illuminate\Support\Facades\Storage;

class HouseManager extends Component
{
    use WithFileUploads;

    public $houses;
    public $editingId = null;
    public $title, $location, $description, $price_per_night, $max_guests, $image_url;
    public $image;
    public $discount_percent, $discount_amount, $promotion_text, $status;
    public $promotion_start, $promotion_end;

    protected $rules = [
        'title' => 'required|string|max:255',
        'location' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'price_per_night' => 'required|numeric|min:0',
        'max_guests' => 'required|integer|min:1',
        'image_url' => 'nullable|string|max:255',
        'image' => 'nullable|image|max:2048',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'discount_amount' => 'nullable|numeric|min:0',
        'promotion_text' => 'nullable|string|max:500',
        'status' => 'nullable|in:popular,hit,new,featured',
        'promotion_start' => 'nullable|date',
        'promotion_end' => 'nullable|date|after_or_equal:promotion_start',
    ];

    public function mount()
    {
        $this->loadHouses();
    }

    public function loadHouses()
    {
        $this->houses = House::latest()->get();
    }

    public function edit($id)
    {
        $house = House::findOrFail($id);

        $this->editingId = $house->id;
        $this->title = $house->title;
        $this->location = $house->location;
        $this->description = $house->description;
        $this->price_per_night = $house->price_per_night;
        $this->max_guests = $house->max_guests;
        $this->image_url = $house->image_url;
        $this->image = null;
        $this->discount_percent = $house->discount_percent;
        $this->discount_amount = $house->discount_amount;
        $this->promotion_text = $house->promotion_text;
        $this->status = $house->status;
        $this->promotion_start = $house->promotion_start?->format('Y-m-d');
        $this->promotion_end = $house->promotion_end?->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        $imageUrl = $this->image_url;

        if ($this->image) {
            $path = $this->image->store('images/houses', 'public');
            $imageUrl = Storage::url($path);
        }

        House::updateOrCreate(
            ['id' => $this->editingId],
            [
                'title' => $this->title,
                'location' => $this->location,
                'description' => $this->description,
                'price_per_night' => $this->price_per_night,
                'max_guests' => $this->max_guests,
                'image_url' => $imageUrl,
                'discount_percent' => $this->discount_percent,
                'discount_amount' => $this->discount_amount,
                'promotion_text' => $this->promotion_text,
                'status' => $this->status,
                'promotion_start' => $this->promotion_start,
                'promotion_end' => $this->promotion_end,
            ]
        );

        $this->reset(['editingId', 'title', 'location', 'description', 'price_per_night', 'max_guests', 'image_url', 'image', 'discount_percent', 'discount_amount', 'promotion_text', 'status', 'promotion_start', 'promotion_end']);
        $this->loadHouses();

        session()->flash('message', 'Домик сохранён');
    }

    public function delete($id)
    {
        House::findOrFail($id)->delete();
        $this->loadHouses();

        session()->flash('message', 'Домик удалён');
    }

    public function render()
    {
        return view('livewire.admin.house-manager')
            ->layout('layouts.app'); // ВАЖНО
    }
}
