<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\House;
use App\Models\ExtraService;
use Illuminate\Support\Facades\Schema;

class ExtraServicesManager extends Component
{
    public $houses;
    public $selectedHouseId;

    public $services = [];
    public $editingId = null;
    public $name;
    public $description;
    public $price = 0;
    public $is_active = true;

    protected $rules = [
        'selectedHouseId' => 'required|exists:houses,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'selectedHouseId.required' => 'Выберите домик, к которому относится услуга.',
        'selectedHouseId.exists'   => 'Выбранный домик не найден.',
        'name.required'            => 'Название обязательно для заполнения.',
        'name.max'                 => 'Название не должно быть длиннее :max символов.',
        'description.string'       => 'Описание должно быть строкой.',
        'price.required'           => 'Укажите цену услуги.',
        'price.numeric'            => 'Цена должна быть числом.',
        'price.min'                => 'Цена не может быть отрицательной.',
    ];

    public function mount(): void
    {
        $this->houses = House::orderBy('title')->get();
        $this->selectedHouseId = optional($this->houses->first())->id;

        if ($this->selectedHouseId) {
            $this->loadServices();
        }
    }

    public function updatedSelectedHouseId(): void
    {
        $this->resetForm();
        $this->loadServices();
    }

    public function loadServices(): void
    {
        if (!Schema::hasTable('extra_services')) {
            $this->services = [];
            return;
        }

        if (!$this->selectedHouseId) {
            $this->services = [];
            return;
        }

        $this->services = ExtraService::where('house_id', $this->selectedHouseId)
            ->orderBy('name')
            ->get();
    }

    public function edit(int $id): void
    {
        $service = ExtraService::where('house_id', $this->selectedHouseId)->findOrFail($id);

        $this->editingId = $service->id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->price = $service->price;
        $this->is_active = $service->is_active;
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'description', 'price', 'is_active']);
        $this->is_active = true;
    }

    public function save(): void
    {
        if (!Schema::hasTable('extra_services')) {
            session()->flash('error', 'Таблица доп. услуг ещё не создана. Запустите php artisan migrate.');
            return;
        }

        $this->validate();

        ExtraService::updateOrCreate(
            ['id' => $this->editingId],
            [
                'house_id' => $this->selectedHouseId,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'is_active' => $this->is_active,
            ]
        );

        $this->resetForm();
        $this->loadServices();

        session()->flash('message', 'Дополнительная услуга сохранена');
    }

    public function delete(int $id): void
    {
        if (!Schema::hasTable('extra_services')) {
            session()->flash('error', 'Таблица доп. услуг ещё не создана. Запустите php artisan migrate.');
            return;
        }

        ExtraService::where('house_id', $this->selectedHouseId)->findOrFail($id)->delete();
        $this->loadServices();

        session()->flash('message', 'Дополнительная услуга удалена');
    }

    public function render()
    {
        return view('livewire.admin.extra-services-manager')
            ->layout('layouts.app');
    }
}

