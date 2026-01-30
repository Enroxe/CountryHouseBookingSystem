<div class="bg-white shadow-lg rounded-2xl p-6 sticky top-4">
    <h2 class="text-2xl font-bold mb-6 text-black">Забронировать домик</h2>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @auth
    <form method="GET" action="{{ route('booking.payment') }}" class="space-y-4">
            <input type="hidden" name="house" value="{{ $house->id }}">

            {{-- Календарь занятости (доступен всем для просмотра) --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-black">Календарь занятости</h3>
                    <div class="flex gap-2">
                        <button type="button" wire:click="previousMonth"
                                class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-300 text-slate-600 hover:bg-slate-100">
                            ‹
                        </button>
                        <div class="text-xs font-medium text-black">
                            {{ \Carbon\Carbon::create($calendar_year, $calendar_month, 1)->translatedFormat('F Y') }}
                        </div>
                        <button type="button" wire:click="nextMonth"
                                class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-300 text-slate-600 hover:bg-slate-100">
                            ›
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-[11px] text-slate-500">
                    <div class="text-center">Пн</div>
                    <div class="text-center">Вт</div>
                    <div class="text-center">Ср</div>
                    <div class="text-center">Чт</div>
                    <div class="text-center">Пт</div>
                    <div class="text-center">Сб</div>
                    <div class="text-center">Вс</div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-xs">
                    @foreach($calendar_days as $day)
                        @if(is_null($day))
                            <div class="h-8"></div>
                        @else
                            @php
                                $dateString = $day['date']->toDateString();
                                $isSelectedStart = $start_date && $dateString === \Carbon\Carbon::parse($start_date)->toDateString();
                                $isSelectedEnd = $end_date && $dateString === \Carbon\Carbon::parse($end_date)->toDateString();
                                $inRange = $start_date && $end_date &&
                                    \Carbon\Carbon::parse($dateString)->between(
                                        \Carbon\Carbon::parse($start_date),
                                        \Carbon\Carbon::parse($end_date)->subDay()
                                    );
                            @endphp

                            @if(!$day['is_available'])
                                <div class="h-8 flex items-center justify-center rounded-lg bg-slate-200 text-slate-400 text-xs cursor-not-allowed"
                                     title="Дата уже забронирована">
                                    {{ $day['date']->day }}
                                </div>
                            @else
                                @auth
                                    <button type="button"
                                            wire:click="selectDate('{{ $dateString }}')"
                                            class="h-8 flex items-center justify-center rounded-lg text-xs
                                                @if($isSelectedStart || $isSelectedEnd)
                                                    bg-sky-600 text-white
                                                @elseif($inRange)
                                                    bg-sky-100 text-sky-700
                                                @elseif($day['is_today'])
                                                    border border-sky-500 text-sky-600
                                                @else
                                                    bg-white border border-slate-200 text-black hover:bg-sky-50
                                                @endif">
                                        {{ $day['date']->day }}
                                    </button>
                                @else
                                    <div class="h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 text-xs cursor-not-allowed"
                                         title="Войдите для выбора дат">
                                        {{ $day['date']->day }}
                                    </div>
                                @endauth
                            @endif
                        @endif
                    @endforeach
                </div>

                <p class="text-[11px] text-slate-500">
                    Серым отмечены занятые даты — их нельзя выбрать для бронирования.
                    @guest
                        <span class="block mt-1 text-sky-600 font-semibold">Войдите, чтобы выбрать даты для бронирования.</span>
                    @endguest
                </p>
            </div>

            @auth
            {{-- Скрытые поля с выбранными датами для передачи в контроллер оплаты --}}
            <input type="hidden" name="start_date" value="{{ $start_date }}">
            <input type="hidden" name="end_date" value="{{ $end_date }}">

            <div>
                <label class="block text-sm font-medium text-black mb-2">Количество гостей</label>
                <input type="number" name="guests" min="1" max="{{ $house->max_guests }}" wire:model="guests_count"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                @error('guests_count') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            @if(!empty($extra_services))
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 space-y-3">
                    <h3 class="text-sm font-semibold text-black mb-1">Дополнительные услуги</h3>
                    <p class="text-xs text-slate-500 mb-2">
                        Выберите опции, которые хотите добавить к бронированию. Стоимость указывается за всё бронирование.
                    </p>

                    <div class="space-y-2">
                        @foreach($extra_services as $service)
                            <label class="flex items-start gap-3 text-sm">
                                <input type="checkbox"
                                       class="mt-1 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                                       wire:model="selected_services"
                                       value="{{ $service->id }}">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-medium text-black">{{ $service->name }}</span>
                                        <span class="text-sm font-semibold text-sky-600">
                                            + {{ number_format($service->price, 0, ',', ' ') }} ₽
                                        </span>
                                    </div>
                                    @if($service->description)
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $service->description }}
                                        </p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($total_amount > 0)
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-black">Стоимость за ночь:</span>
                        <div class="text-right">
                            @if($house->hasActiveDiscount())
                                <div class="text-xs text-slate-400 line-through">
                                    {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                                </div>
                                <span class="font-semibold text-red-600">
                                    {{ number_format($house->final_price, 0, ',', ' ') }} ₽
                                </span>
                                <div class="text-xs text-red-600 font-semibold">
                                    {{ $house->discount_display }}
                                </div>
                            @else
                                <span class="font-semibold text-black">
                                    {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-black">Количество ночей:</span>
                        <span class="font-semibold text-black">
                            @if($start_date && $end_date)
                                {{ \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) }}
                            @else
                                0
                            @endif
                        </span>
                    </div>
                    @if($extras_total > 0)
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-black">Доп. услуги:</span>
                            <span class="font-semibold text-black">
                                + {{ number_format($extras_total, 0, ',', ' ') }} ₽
                            </span>
                        </div>
                    @endif

                    <div class="border-t border-slate-300 pt-2 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-black">Итого:</span>
                            <span class="text-2xl font-bold text-sky-600">{{ number_format($total_amount, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="pt-2">
                <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 text-white font-semibold hover:from-sky-700 hover:to-blue-700 transition shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Перейти к оплате
                </button>
            </div>
            @endauth
    @else
        <div class="space-y-4">
            <div class="bg-sky-50 border border-sky-200 rounded-xl p-4">
                <p class="text-sm text-slate-700 mb-4">
                    Для бронирования домика необходимо войти в систему.
                </p>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('login') }}"
                       class="w-full inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 text-white font-semibold hover:from-sky-700 hover:to-blue-700 transition shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Войти для бронирования
                    </a>
                    <a href="{{ route('register') }}"
                       class="w-full inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl border-2 border-sky-600 text-sky-600 font-semibold hover:bg-sky-50 transition">
                        Зарегистрироваться
                    </a>
                </div>
            </div>

            {{-- Показываем информацию о цене для неавторизованных --}}
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-black">Цена за ночь:</span>
                    <div class="text-right">
                        @if($house->hasActiveDiscount())
                            <div class="text-xs text-slate-400 line-through">
                                {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                            </div>
                            <span class="font-semibold text-red-600">
                                {{ number_format($house->final_price, 0, ',', ' ') }} ₽
                            </span>
                            <div class="text-xs text-red-600 font-semibold">
                                {{ $house->discount_display }}
                            </div>
                        @else
                            <span class="font-semibold text-black">
                                {{ number_format($house->price_per_night, 0, ',', ' ') }} ₽
                            </span>
                        @endif
                    </div>
                </div>
                @if($house->promotion_text && $house->hasActiveDiscount())
                    <div class="mt-2 pt-2 border-t border-slate-200">
                        <p class="text-xs text-sky-700 font-semibold">{{ $house->promotion_text }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endauth
</div>
