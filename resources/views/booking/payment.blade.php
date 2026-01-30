@extends('layouts.app')

@section('title', 'Оплата бронирования')

@section('content')
    <div class="bg-gradient-to-b from-sky-100 to-white min-h-[calc(100vh-80px)]">
        <div class="max-w-4xl mx-auto px-4 py-10">
            <a href="{{ route('houses.show', $house) }}"
               class="inline-flex items-center gap-2 text-sky-600 hover:text-sky-700 text-sm mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Вернуться к домику
            </a>

            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="grid md:grid-cols-2">
                    <div class="p-6 md:p-8 border-b md:border-b-0 md:border-r border-slate-100 bg-slate-50">
                        <h1 class="text-2xl font-bold text-black mb-4">Оплата бронирования</h1>
                        <p class="text-sm text-slate-600 mb-6">
                            Введите данные карты, чтобы оплатить бронирование. Списания в тестовом режиме не происходит.
                        </p>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Домик</span>
                                <span class="font-medium text-black text-right">{{ $house->title }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Даты</span>
                                <span class="font-medium text-black text-right">
                                    {{ \Carbon\Carbon::parse($start_date)->format('d.m.Y') }}
                                    —
                                    {{ \Carbon\Carbon::parse($end_date)->format('d.m.Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Гостей</span>
                                <span class="font-medium text-black">{{ $guests }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Базовая стоимость</span>
                                <span class="font-medium text-black text-right">
                                    {{ number_format($base_total, 0, ',', ' ') }} ₽
                                </span>
                            </div>

                            @if(!empty($extras) && count($extras) > 0)
                                <div class="mt-3 pt-3 border-t border-dashed">
                                    <div class="text-xs font-semibold text-slate-500 mb-1">Дополнительные услуги</div>
                                    <ul class="space-y-1 text-xs text-slate-700">
                                        @foreach($extras as $extra)
                                            <li class="flex justify-between">
                                                <span>{{ $extra->name }}</span>
                                                <span>+ {{ number_format($extra->price, 0, ',', ' ') }} ₽</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="flex justify-between mt-2">
                                        <span class="text-slate-500">Всего за услуги</span>
                                        <span class="font-medium text-black">
                                            + {{ number_format($extras_total, 0, ',', ' ') }} ₽
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div class="border-t border-dashed my-3"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">К оплате</span>
                                <span class="text-2xl font-bold text-sky-600">
                                    {{ number_format($total, 0, ',', ' ') }} ₽
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 space-y-5">
                        <h2 class="text-lg font-semibold text-black mb-2">Оплата картой</h2>

                        @if($errors->any())
                            <div class="rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm p-3">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('booking.payment.pay') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="house_id" value="{{ $house->id }}">
                            <input type="hidden" name="start_date" value="{{ $start_date }}">
                            <input type="hidden" name="end_date" value="{{ $end_date }}">
                            <input type="hidden" name="guests" value="{{ $guests }}">
                            <input type="hidden" name="total" value="{{ $total }}">

                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-600">Номер карты</label>
                                <input name="card_number" inputmode="numeric" minlength="12" maxlength="19"
                                       class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300"
                                       placeholder="0000 0000 0000 0000" required>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-slate-600">Месяц</label>
                                    <input name="card_exp_month" type="number" min="1" max="12"
                                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300"
                                           placeholder="MM" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-slate-600">Год</label>
                                    <input name="card_exp_year" type="number" min="{{ now()->format('y') }}"
                                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300"
                                           placeholder="YY" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-slate-600">Имя на карте</label>
                                    <input name="card_holder" type="text"
                                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300"
                                           placeholder="IVAN IVANOV" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-slate-600">CVV</label>
                                    <input name="card_cvv" type="password" inputmode="numeric" minlength="3" maxlength="4"
                                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300"
                                           placeholder="123" required>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-xs text-yellow-800">
                                Оплата производится в тестовом режиме — средства не списываются. После нажатия «Оплатить»
                                бронь создастся со статусом «оплачено».
                            </div>

                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold text-sm shadow-lg hover:from-green-700 hover:to-green-800 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Оплатить бронирование
                            </button>

                            <p class="text-[11px] text-slate-400 text-center">
                                Нажимая кнопку, вы подтверждаете условия бронирования и согласие с политикой обработки данных.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


