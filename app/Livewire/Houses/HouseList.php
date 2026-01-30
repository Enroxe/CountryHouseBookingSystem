<?php

namespace App\Livewire\Houses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\House;
use App\Models\Booking;
use Carbon\Carbon;

class HouseList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $guests = null;
    public $location = '';
    public $min_price = null;
    public $max_price = null;
    public $sort = 'recommended';
    public $available_from = null;
    public $available_to = null;

    // сбрасываем страницу при любом изменении фильтра
    public function updating($name, $value)
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = House::query();

        // Поиск
        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('location', 'like', "%{$this->search}%");
            });
        }

        // Локация
        if ($this->location !== '') {
            $query->where('location', 'like', "%{$this->location}%");
        }

        // Гостей
        if (!is_null($this->guests) && $this->guests > 0) {
            $query->where('max_guests', '>=', $this->guests);
        }

        // Цена от
        if (!is_null($this->min_price)) {
            $query->where('price_per_night', '>=', $this->min_price);
        }

        // Цена до
        if (!is_null($this->max_price)) {
            $query->where('price_per_night', '<=', $this->max_price);
        }

        // Фильтр по свободным датам
        if ($this->available_from && $this->available_to) {
            $from = Carbon::parse($this->available_from)->startOfDay();
            $to = Carbon::parse($this->available_to)->endOfDay();

            $query->whereDoesntHave('bookings', function ($q) use ($from, $to) {
                $q->where('status', '!=', 'cancelled')
                  ->where(function ($query) use ($from, $to) {
                      $query->whereBetween('start_date', [$from, $to])
                            ->orWhereBetween('end_date', [$from, $to])
                            ->orWhere(function ($q) use ($from, $to) {
                                $q->where('start_date', '<=', $from)
                                  ->where('end_date', '>=', $to);
                            });
                  });
            });
        }

        // Сортировка
        match ($this->sort) {
            'price_asc'  => $query->orderBy('price_per_night', 'asc'),
            'price_desc' => $query->orderBy('price_per_night', 'desc'),
            default      => $query->latest(),
        };

        return view('livewire.houses.house-list', [
            'houses' => $query->paginate(12),
        ]);
    }
}
