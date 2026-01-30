@section('title', 'Система бронирования загородных домов')

@php
    // Берём 3 последних домика для превью на главной
    $houses = \App\Models\House::latest()->take(3)->get();
@endphp

<div class="min-h-[calc(100vh-80px)]">
    <!-- Карусель с акциями - на всю ширину -->
    <div class="relative overflow-hidden bg-sky-600 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-10 sm:py-12">
            <div class="relative h-[400px] md:h-[500px]">
                @foreach($promotions as $index => $promo)
                    <div class="absolute inset-0 transition-all duration-700 ease-in-out transform
                        {{ $currentSlide === $index ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8 pointer-events-none' }}">
                        <div class="grid md:grid-cols-2 gap-8 items-center h-full">
                            <div class="text-white">
                                <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold mb-4">
                                    {{ $promo['badge'] }}
                                </span>
                                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-white">{{ $promo['title'] }}</h2>
                                <p class="text-xl text-white/90 mb-6">{{ $promo['description'] }}</p>
                                @auth
                                    <a href="{{ route('houses.index') }}"
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-white text-sky-600 font-semibold rounded-xl hover:bg-sky-50 transition shadow-lg">
                                        Выбрать домик
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('houses.index') }}"
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-white text-sky-600 font-semibold rounded-xl hover:bg-sky-50 transition shadow-lg">
                                        Посмотреть домики
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endauth
                            </div>
                            <div class="hidden md:block">
                                <img src="{{ $promo['image'] }}" alt="{{ $promo['title'] }}"
                                     class="w-full h-64 md:h-80 object-cover rounded-2xl shadow-2xl"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200&h=600&fit=crop';">
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Стрелки навигации -->
                <button wire:click="prevSlide"
                        class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full p-3 transition z-10">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button wire:click="nextSlide"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full p-3 transition z-10">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Навигация карусели -->
            <div class="flex justify-center gap-2 mt-6">
                @foreach($promotions as $index => $promo)
                    <button wire:click="goToSlide({{ $index }})"
                            class="w-3 h-3 rounded-full transition-all duration-300 {{ $currentSlide === $index ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/70' }}">
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pt-6 space-y-4 pb-10">
        <div class="bg-white rounded-3xl p-6 sm:p-8 mt-6">
        <!-- Популярные домики месяца -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-black mb-2">Популярные домики месяца</h2>
                    <p class="text-black">Самые востребованные варианты для отдыха</p>
                </div>
                @auth
                    <a href="{{ route('houses.index') }}" class="text-sky-600 hover:text-sky-700 font-semibold">
                        Смотреть все →
                    </a>
                @endauth
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @forelse($popularHouses as $house)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                        <div class="relative overflow-hidden bg-slate-200">
                            @if($house->image_url)
                                <img src="{{ $house->image_url }}"
                                     alt="{{ $house->title }}"
                                     class="w-full h-48 object-cover group-hover:scale-110 transition duration-500"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop';">
                            @else
                                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop"
                                     alt="{{ $house->title }}"
                                     class="w-full h-48 object-cover group-hover:scale-110 transition duration-500">
                            @endif

                            @if($house->status)
                                <div class="absolute top-4 left-4">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold text-white shadow-lg
                                        @if($house->status === 'popular') bg-purple-600
                                        @elseif($house->status === 'hit') bg-red-600
                                        @elseif($house->status === 'new') bg-green-600
                                        @elseif($house->status === 'featured') bg-sky-600
                                        @endif">
                                        @if($house->status === 'popular') Популярно
                                        @elseif($house->status === 'hit') Хит
                                        @elseif($house->status === 'new') Новинка
                                        @elseif($house->status === 'featured') Рекомендуем
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if(method_exists($house, 'hasActiveDiscount') && $house->hasActiveDiscount())
                                <div class="absolute top-4 right-4">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold text-white bg-red-600 shadow-lg">
                                        {{ $house->discount_display }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm text-black">{{ $house->location ?? 'Не указано' }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-black mb-2">{{ $house->title }}</h3>

                            @if(method_exists($house, 'promotion_text') && $house->promotion_text && $house->hasActiveDiscount())
                                <div class="mb-2 p-2 bg-gradient-to-r from-sky-50 to-blue-50 rounded-lg border border-sky-100">
                                    <p class="text-xs font-semibold text-sky-700">{{ $house->promotion_text }}</p>
                                </div>
                            @endif

                            <p class="text-black text-sm mb-4 line-clamp-2">{{ $house->description }}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if(method_exists($house, 'hasActiveDiscount') && $house->hasActiveDiscount())
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-semibold text-slate-400 line-through">
                                                {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                                            </div>
                                            <div class="text-2xl font-bold text-sky-600">
                                                {{ number_format($house->final_price, 0, ',', ' ') }} ₽
                                            </div>
                                        </div>
                                        <div class="text-xs text-sky-700 font-semibold">
                                            {{ $house->discount_display }} · за ночь
                                        </div>
                                    @else
                                        <div class="text-2xl font-bold text-sky-600">
                                            {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                                        </div>
                                        <div class="text-xs text-black">за ночь</div>
                                    @endif
                                </div>
                                <a href="{{ route('houses.show', $house) }}"
                                   class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition font-semibold text-sm">
                                    @auth
                                        Забронировать
                                    @else
                                        Посмотреть детали
                                    @endauth
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-black">
                        Пока нет доступных домиков
                    </div>
                @endforelse
            </div>
        </div>
        </div>

        <!-- Основной контент -->
        <div class="bg-white rounded-3xl p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="w-12 h-12 rounded-full shadow-lg" onerror="this.style.display='none'">
                        <span class="text-2xl font-bold text-sky-700">Country Houses Booking</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-black">
                        Найдите идеальный загородный дом<br>
                        <span class="text-sky-600">для отдыха на природе</span>
                    </h1>

                    <p class="text-lg text-black mb-6">
                        Бронируйте уютные домики с камином, баней и панорамным видом — всего в паре кликов.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('houses.index') }}"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-sky-600 text-white font-semibold shadow-lg hover:bg-sky-700 transition">
                                Перейти к домикам
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-sky-600 text-white font-semibold shadow-lg hover:bg-sky-700 transition">
                                Войти и забронировать
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-sky-600 text-sky-600 font-semibold hover:bg-sky-50 transition">
                                Зарегистрироваться
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="flex-1 grid grid-cols-2 gap-4">
                    @foreach($houses as $house)
                        <div class="bg-slate-50 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                            @if($house->image_url)
                                <img src="{{ $house->image_url }}"
                                     alt="{{ $house->title }}"
                                     class="w-full h-40 sm:h-48 object-cover"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=300&fit=crop';">
                            @else
                                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=300&fit=crop"
                                     alt="{{ $house->title }}"
                                     class="w-full h-40 sm:h-48 object-cover">
                            @endif
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-sm text-black">{{ $house->location ?? 'Не указано' }}</div>
                                    @if($house->status)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold text-white
                                            @if($house->status === 'popular') bg-purple-600
                                            @elseif($house->status === 'hit') bg-red-600
                                            @elseif($house->status === 'new') bg-green-600
                                            @elseif($house->status === 'featured') bg-sky-600
                                            @endif">
                                            @if($house->status === 'popular') Популярно
                                            @elseif($house->status === 'hit') Хит
                                            @elseif($house->status === 'new') Новинка
                                            @elseif($house->status === 'featured') Рекомендуем
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                <div class="font-semibold text-black text-sm mb-2 line-clamp-1">{{ $house->title }}</div>
                                <div class="text-sky-600 font-bold">
                                    @if(method_exists($house, 'hasActiveDiscount') && $house->hasActiveDiscount())
                                        <span class="text-xs text-slate-400 line-through mr-1">
                                            {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                                        </span>
                                        <span>
                                            от {{ number_format($house->final_price, 0, ',', ' ') }} ₽ / ночь
                                        </span>
                                    @else
                                        от {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽ / ночь
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    let autoSlideInterval;

    function startAutoSlide() {
        if (autoSlideInterval) clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(() => {
            $wire.nextSlide();
        }, 5000);
    }

    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
            autoSlideInterval = null;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const carousel = document.querySelector('[wire\\:id]');
        if (carousel) {
            // Запускаем автопереключение при загрузке
            startAutoSlide();

            // Останавливаем при наведении
            carousel.addEventListener('mouseenter', stopAutoSlide);

            // Возобновляем при уходе курсора
            carousel.addEventListener('mouseleave', startAutoSlide);
        }
    });

    // Перезапускаем при обновлении Livewire
    Livewire.hook('morph.updated', () => {
        const carousel = document.querySelector('[wire\\:id]');
        if (carousel && !autoSlideInterval) {
            startAutoSlide();
        }
    });
</script>
@endscript
