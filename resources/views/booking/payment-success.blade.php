@extends('layouts.app')

@section('title', 'Оплата успешно завершена')

@section('content')
    <div class="bg-gradient-to-b from-green-50 to-white min-h-[calc(100vh-80px)]">
        <div class="max-w-3xl mx-auto px-4 py-12">
            <div class="bg-white border border-green-100 shadow-2xl rounded-3xl p-8 text-center">
                <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-green-100 text-green-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-black mb-2">Оплата прошла успешно</h1>
                <p class="text-slate-600 mb-6">Ваше бронирование подтверждено и оплачено.</p>

                <div class="bg-slate-50 rounded-2xl p-4 text-left inline-block text-sm text-black">
                    <div class="flex justify-between gap-6 mb-1">
                        <span class="text-slate-500">Домик</span>
                        <span class="font-semibold">{{ $booking->house->title }}</span>
                    </div>
                    <div class="flex justify-between gap-6 mb-1">
                        <span class="text-slate-500">Даты</span>
                        <span class="font-semibold">
                            {{ $booking->start_date->format('d.m.Y') }} — {{ $booking->end_date->format('d.m.Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between gap-6 mb-1">
                        <span class="text-slate-500">Гостей</span>
                        <span class="font-semibold">{{ $booking->guests_count }}</span>
                    </div>
                    <div class="flex justify-between gap-6 border-t border-dashed pt-2 mt-2">
                        <span class="text-slate-500">Сумма</span>
                        <span class="font-semibold text-green-700">
                            {{ number_format($booking->total_amount, 0, ',', ' ') }} ₽
                        </span>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('houses.show', $booking->house) }}"
                       class="px-5 py-3 rounded-xl bg-black text-white text-sm font-semibold hover:bg-gray-800 transition">
                        Вернуться к домику
                    </a>
                    <a href="{{ route('houses.index') }}"
                       class="px-5 py-3 rounded-xl border border-slate-300 text-sm font-semibold text-black hover:bg-slate-50 transition">
                        Ко всем домикам
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

