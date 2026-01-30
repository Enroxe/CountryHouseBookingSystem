<div class="bg-gradient-to-b from-sky-50 to-white min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">Дополнительные услуги</h1>
            <p class="text-slate-600">Добавляйте и управляйте доп. опциями для домиков</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-6 md:p-8 mb-8">
            <div class="grid md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Домик
                    </label>
                    <select wire:model="selectedHouseId"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                        <option value="">Выберите домик</option>
                        @foreach($houses as $house)
                            <option value="{{ $house->id }}">{{ $house->title }}</option>
                        @endforeach
                    </select>
                    @error('selectedHouseId')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror

                    <p class="text-xs text-slate-500 mt-3">
                        Список доп. услуг будет относиться к выбранному домику.
                    </p>
                </div>

                <div class="md:col-span-2">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">
                        {{ $editingId ? 'Редактировать услугу' : 'Добавить услугу' }}
                    </h2>

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Название</label>
                            <input type="text" wire:model="name"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                   placeholder="Например: Баня, Мангал, Завтрак">
                            @error('name')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Описание</label>
                            <textarea wire:model="description" rows="3"
                                      class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none"
                                      placeholder="Кратко опишите, что входит в услугу"></textarea>
                            @error('description')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 items-center">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Цена (₽)</label>
                                <input type="number" min="0" step="100" wire:model="price"
                                       class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                       placeholder="Например: 1500">
                                @error('price')
                                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center gap-2 mt-4 md:mt-7">
                                <button type="button"
                                        wire:click="$set('is_active', {{ $is_active ? 'false' : 'true' }})"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200
                                            {{ $is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                    <span class="sr-only">Активна</span>
                                    <span aria-hidden="true"
                                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200
                                            {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="text-sm text-slate-700">
                                    {{ $is_active ? 'Активна' : 'Выключена' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-200">
                            @if($editingId)
                                <button type="button" wire:click="resetForm"
                                        class="px-4 py-2 rounded-xl border-2 border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                                    Отмена
                                </button>
                            @endif
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-md">
                                {{ $editingId ? 'Сохранить изменения' : 'Добавить услугу' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($selectedHouseId)
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900">Услуги домика</h2>
                    <span class="text-sm text-slate-600">
                        Всего: {{ count($services) }}
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($services as $service)
                        <div class="px-6 py-4 flex items-start justify-between gap-4 hover:bg-slate-50">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-slate-900">
                                        {{ $service->name }}
                                    </span>
                                    @if(!$service->is_active)
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-500">
                                            Выключена
                                        </span>
                                    @endif
                                </div>
                                @if($service->description)
                                    <p class="text-xs text-slate-600 mb-1">
                                        {{ $service->description }}
                                    </p>
                                @endif
                                <div class="text-xs text-emerald-600 font-semibold">
                                    {{ number_format($service->price, 0, ',', ' ') }} ₽ за бронирование
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="edit({{ $service->id }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Редактировать
                                </button>
                                <button wire:click="delete({{ $service->id }})"
                                        onclick="if(!confirm('Удалить услугу «{{ $service->name }}»?')) return false;"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Удалить
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center text-sm text-slate-500">
                            Для выбранного домика пока нет доп. услуг.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</div>

