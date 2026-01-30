<div class="bg-gradient-to-b from-sky-50 to-white min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">Управление домиками</h1>
            <p class="text-slate-600">Добавляйте, редактируйте и управляйте загородными домиками</p>
        </div>

        <!-- Уведомления -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl shadow-sm flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Форма добавления/редактирования -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-sky-100 rounded-lg">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900">
                    {{ $editingId ? 'Редактировать домик' : 'Добавить новый домик' }}
                </h2>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Название <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="title"
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                               placeholder="Например: Лесной домик у озера">
                        @error('title')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Локация</label>
                        <input type="text" wire:model="location"
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                               placeholder="Например: Ленинградская область">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Описание</label>
                    <textarea wire:model="description" rows="4"
                              class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition resize-none"
                              placeholder="Опишите особенности домика..."></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Цена за ночь (₽) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="price_per_night" min="0" step="100"
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                               placeholder="7500">
                        @error('price_per_night')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Макс. гостей <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="max_guests" min="1"
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                               placeholder="4">
                        @error('max_guests')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Скидки и акции -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-700 mb-4">Скидки и акции</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Скидка в процентах (%)</label>
                            <input type="number" wire:model="discount_percent" min="0" max="100" step="1"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                                   placeholder="Например: 15">
                            @error('discount_percent')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">Оставьте пустым, если используете фиксированную скидку</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Фиксированная скидка (₽)</label>
                            <input type="number" wire:model="discount_amount" min="0" step="100"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                                   placeholder="Например: 1000">
                            @error('discount_amount')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">Оставьте пустым, если используете процентную скидку</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Текст акции</label>
                        <input type="text" wire:model="promotion_text" maxlength="500"
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                               placeholder="Например: Скидка 20% до конца месяца!">
                        @error('promotion_text')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Дата начала акции</label>
                            <input type="date" wire:model="promotion_start"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                            @error('promotion_start')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Дата окончания акции</label>
                            <input type="date" wire:model="promotion_end"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                            @error('promotion_end')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Статусы -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-700 mb-4">Статус домика</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="status" value="popular" class="text-sky-600 focus:ring-sky-500">
                            <span class="text-sm text-slate-700">Популярно</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="status" value="hit" class="text-sky-600 focus:ring-sky-500">
                            <span class="text-sm text-slate-700">Хит</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="status" value="new" class="text-sky-600 focus:ring-sky-500">
                            <span class="text-sm text-slate-700">Новинка</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="status" value="featured" class="text-sky-600 focus:ring-sky-500">
                            <span class="text-sm text-slate-700">Рекомендуем</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="status" value="" class="text-sky-600 focus:ring-sky-500">
                            <span class="text-sm text-slate-700">Без статуса</span>
                        </label>
                    </div>
                </div>

                <!-- Загрузка изображений -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-700 mb-4">Изображение домика</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Загрузить с компьютера
                            </label>
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 hover:border-sky-400 transition">
                                <input type="file" wire:model="image" accept="image/jpeg,image/png,image/jpg"
                                       class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer">
                                @error('image')
                                    <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span>
                                @enderror
                                <p class="text-xs text-slate-500 mt-2">JPG/PNG, до 2 МБ</p>

                                @if($image)
                                    <div class="mt-4">
                                        <p class="text-xs font-medium text-slate-600 mb-2">Предпросмотр:</p>
                                        <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                             class="w-full h-48 object-cover rounded-lg border border-slate-200 shadow-sm">
                                    </div>
                                @elseif($image_url)
                                    <div class="mt-4">
                                        <p class="text-xs font-medium text-slate-600 mb-2">Текущее изображение:</p>
                                        <img src="{{ $image_url }}" alt="Current"
                                             class="w-full h-48 object-cover rounded-lg border border-slate-200 shadow-sm"
                                             onerror="this.src='https://via.placeholder.com/400x300?text=Изображение+не+найдено'">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Или указать URL изображения
                            </label>
                            <input type="text" wire:model="image_url"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                                   placeholder="https://example.com/image.jpg">
                            @error('image_url')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-slate-500 mt-2">Если указать и URL, и файл, будет использован загруженный файл</p>
                        </div>
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    @if($editingId)
                        <button type="button" wire:click="$set('editingId', null)"
                                wire:loading.attr="disabled"
                                class="px-6 py-3 rounded-xl border-2 border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 hover:border-slate-400 disabled:opacity-50 transition shadow-sm">
                            Отмена
                        </button>
                    @endif
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 text-white font-semibold hover:from-sky-700 hover:to-blue-700 disabled:opacity-50 transition shadow-lg hover:shadow-xl flex items-center gap-2">
                        <span wire:loading.remove wire:target="save">
                            @if($editingId)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Обновить домик
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Добавить домик
                            @endif
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Сохранение...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Список домиков -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Список домиков
                    </h2>
                    <span class="text-sm text-slate-600 bg-white px-3 py-1 rounded-full border border-slate-200">
                        Всего: {{ $houses->count() }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Название</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Локация</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Цена</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Гостей</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($houses as $house)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-slate-900">#{{ $house->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-900">{{ $house->title }}</div>
                                    @if($house->description)
                                        <div class="text-xs text-slate-500 mt-1 line-clamp-1">{{ Str::limit($house->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $house->location ?? 'Не указано' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-sky-600">{{ number_format($house->price_per_night, 0, ',', ' ') }} ₽</span>
                                    <div class="text-xs text-slate-500">за ночь</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1 text-sm text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $house->max_guests }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="edit({{ $house->id }})"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-sky-600 bg-sky-50 rounded-lg hover:bg-sky-100 disabled:opacity-50 transition">
                                            <span wire:loading.remove wire:target="edit({{ $house->id }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Редактировать
                                            </span>
                                            <span wire:loading wire:target="edit({{ $house->id }})" class="flex items-center gap-1">
                                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Загрузка...
                                            </span>
                                        </button>
                                        <button wire:click="delete({{ $house->id }})"
                                                onclick="if(!confirm('Вы уверены, что хотите удалить домик «{{ $house->title }}»?')) return false;"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 disabled:opacity-50 transition">
                                            <span wire:loading.remove wire:target="delete({{ $house->id }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Удалить
                                            </span>
                                            <span wire:loading wire:target="delete({{ $house->id }})" class="flex items-center gap-1">
                                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Удаление...
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <div class="text-slate-500">
                                            <p class="font-medium">Нет домиков</p>
                                            <p class="text-sm">Добавьте первый домик, используя форму выше</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
