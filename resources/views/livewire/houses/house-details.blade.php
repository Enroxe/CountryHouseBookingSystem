<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('houses.index') }}" class="inline-flex items-center gap-2 text-sky-600 hover:text-sky-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Вернуться к списку</span>
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden bg-slate-200">
                @if($house->image_url)
                    <img src="{{ $house->image_url }}"
                         alt="{{ $house->title }}"
                         class="w-full h-64 md:h-96 object-cover"
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=500&fit=crop';">
                @else
                    <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=500&fit=crop"
                         alt="{{ $house->title }}"
                         class="w-full h-64 md:h-96 object-cover">
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm text-black">{{ $house->location ?? 'Не указано' }}</span>
                </div>

                <div class="flex items-start justify-between gap-4 mb-4">
                    <h1 class="text-3xl font-bold text-black">{{ $house->title }}</h1>
                    @if($house->status)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                            @if($house->status === 'popular') bg-purple-100 text-purple-700
                            @elseif($house->status === 'hit') bg-red-100 text-red-700
                            @elseif($house->status === 'new') bg-green-100 text-green-700
                            @elseif($house->status === 'featured') bg-yellow-100 text-yellow-700
                            @endif">
                            @if($house->status === 'popular') Популярно
                            @elseif($house->status === 'hit') Хит
                            @elseif($house->status === 'new') Новинка
                            @elseif($house->status === 'featured') Рекомендуем
                            @endif
                        </span>
                    @endif
                </div>

                @if($house->promotion_text && $house->hasActiveDiscount())
                    <div class="mb-4 p-4 bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 rounded-lg">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2m0 0H5m2 0h2m-2 0a2 2 0 10-4 0m4 0V6a2 2 0 112 2m-6 0a2 2 0 100 4m0-4v13m0-4V10a2 2 0 112 2h-2m-2 0h2m2 0a2 2 0 100 4m0-4v1a2 2 0 002 2h2a2 2 0 002-2v-1m-6 4h6"/>
                            </svg>
                            <span class="font-semibold text-red-700">{{ $house->promotion_text }}</span>
                        </div>
                    </div>
                @endif

                <p class="text-black mb-6 leading-relaxed">{{ $house->description }}</p>

                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-sky-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-black">Цена за ночь</div>
                            <div class="flex items-center gap-2">
                                @if($house->hasActiveDiscount())
                                    <div class="text-lg font-semibold text-slate-400 line-through">
                                        {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                                    </div>
                                    <div class="text-xl font-bold text-red-600">
                                        {{ number_format($house->final_price, 0, ',', ' ') }} ₽
                                    </div>
                                @else
                                    <div class="text-xl font-bold text-sky-600">
                                        {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                                    </div>
                                @endif
                            </div>
                            @if($house->hasActiveDiscount())
                                <div class="text-xs text-red-600 font-semibold mt-1">
                                    {{ $house->discount_display }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-black">Максимум гостей</div>
                            <div class="text-xl font-bold text-green-600">{{ $house->max_guests }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            @livewire('houses.booking-form', ['house' => $house], key('booking-'.$house->id))
        </div>
    </div>
</div>
