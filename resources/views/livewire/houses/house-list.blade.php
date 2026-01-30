<div class="bg-gradient-to-b from-sky-100 to-white min-h-[calc(100vh-80px)]">
    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- Заголовок и фильтры --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-3 text-black">
                    Выберите загородный дом
                </h1>
                <p class="text-black max-w-xl">
                    Найдите идеальный домик по названию, локации, цене и количеству гостей.
                </p>
            </div>

            <div class="bg-white/90 backdrop-blur-sm shadow-lg rounded-2xl p-4 flex flex-wrap gap-4 items-end">

                {{-- Поиск --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Название или описание
                    </label>
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="search"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-300"
                        placeholder="Например: шале, баня, камин"
                    >
                </div>

                {{-- Локация --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Локация
                    </label>
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="location"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-300"
                        placeholder="Например: Карелия"
                    >
                </div>

                {{-- Гости --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Гостей
                    </label>
                    <input
                        type="number"
                        min="1"
                        wire:model.live="guests"
                        placeholder="Любое"
                        class="w-24 border border-slate-200 rounded-xl px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-sky-300"
                    >
                </div>

                {{-- Цена --}}
                <div class="flex gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            Цена от, ₽
                        </label>
                        <input
                            type="number"
                            min="0"
                            wire:model.live="min_price"
                            class="w-28 border border-slate-200 rounded-xl px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-300"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            до, ₽
                        </label>
                        <input
                            type="number"
                            min="0"
                            wire:model.live="max_price"
                            class="w-28 border border-slate-200 rounded-xl px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-300"
                        >
                    </div>
                </div>

                {{-- Свободные даты --}}
                <div class="flex gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            Свободно с
                        </label>
                        <input
                            type="date"
                            wire:model.live="available_from"
                            class="w-32 border border-slate-200 rounded-xl px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-300"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            по
                        </label>
                        <input
                            type="date"
                            wire:model.live="available_to"
                            class="w-32 border border-slate-200 rounded-xl px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-300"
                        >
                    </div>
                </div>

                {{-- Сортировка --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Сортировка
                    </label>
                    <select
                        wire:model.live="sort"
                        class="border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-sky-300"
                    >
                        <option value="recommended">Рекомендованные</option>
                        <option value="price_asc">Цена: по возрастанию</option>
                        <option value="price_desc">Цена: по убыванию</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- Список домиков --}}
        <div class="grid md:grid-cols-3 gap-6">
            @forelse($houses as $house)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col
                            hover:shadow-xl transition group">

                    <div class="relative overflow-hidden bg-slate-200">
                        @if($house->image_url)
                            <img
                                src="{{ $house->image_url }}"
                                alt="{{ $house->title }}"
                                class="w-full h-48 object-cover
                                       group-hover:scale-110 transition duration-500"
                                onerror="this.onerror=null;
                                         this.src='https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop';"
                            >
                        @else
                            <img
                                src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop"
                                alt="{{ $house->title }}"
                                class="w-full h-48 object-cover
                                       group-hover:scale-110 transition duration-500"
                            >
                        @endif

                        @if($house->status)
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold text-white shadow-lg
                                    @if($house->status === 'popular') bg-purple-600
                                    @elseif($house->status === 'hit') bg-red-600
                                    @elseif($house->status === 'new') bg-green-600
                                    @elseif($house->status === 'featured') bg-yellow-600
                                    @endif">
                                    @if($house->status === 'popular') Популярно
                                    @elseif($house->status === 'hit') Хит
                                    @elseif($house->status === 'new') Новинка
                                    @elseif($house->status === 'featured') Рекомендуем
                                    @endif
                                </span>
                            </div>
                        @endif

                        @if($house->hasActiveDiscount())
                            <div class="absolute top-3 right-3">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold text-white bg-red-600 shadow-lg">
                                    {{ $house->discount_display }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        <div class="text-sm text-black mb-1 flex items-center gap-1">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $house->location ?? 'Не указано' }}</span>
                        </div>

                        <h2 class="font-semibold text-lg text-black mb-2">
                            {{ $house->title }}
                        </h2>

                        @if($house->promotion_text && $house->hasActiveDiscount())
                            <div class="mb-2 p-2 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg border border-red-200">
                                <p class="text-xs font-semibold text-red-700">{{ $house->promotion_text }}</p>
                            </div>
                        @endif

                        <p class="text-sm text-black mb-3 line-clamp-3">
                            {{ $house->description }}
                        </p>

                        <div class="mt-auto flex items-center justify-between">
                            <div>
                                @if($house->hasActiveDiscount())
                                    <div class="flex items-center gap-2">
                                        <div class="text-sm font-semibold text-slate-400 line-through">
                                            {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                                        </div>
                                        <div class="text-sky-600 font-bold">
                                            {{ number_format($house->final_price, 0, ',', ' ') }} ₽ / ночь
                                        </div>
                                    </div>
                                @else
                                    <div class="text-sky-600 font-semibold">
                                        {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽ / ночь
                                    </div>
                                @endif
                                <div class="text-xs text-black">
                                    до {{ $house->max_guests }} гостей
                                </div>
                            </div>

                            <a
                                href="{{ route('houses.show', $house) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                                       bg-sky-600 text-white text-sm font-semibold
                                       hover:bg-sky-700 transition shadow-md hover:shadow-lg"
                            >
                                @auth
                                    Забронировать
                                @else
                                    Посмотреть детали
                                @endauth
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-black">
                    Подходящие домики не найдены.
                </div>
            @endforelse
        </div>

        {{-- Пагинация --}}
        <div class="mt-6">
            {{ $houses->links() }}
        </div>

    </div>
</div>
