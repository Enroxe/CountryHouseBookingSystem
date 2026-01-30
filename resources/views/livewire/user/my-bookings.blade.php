<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-black">Мои бронирования</h1>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-6 text-center text-slate-500">
            У вас пока нет бронирований.
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="text-xs text-slate-400">Бронирование #{{ $booking->id }}</div>
                        <div class="text-lg font-semibold text-black">
                            {{ $booking->house->title ?? 'Домик удалён' }}
                        </div>
                        <div class="text-sm text-slate-600">
                            {{ $booking->start_date->format('d.m.Y') }} —
                            {{ $booking->end_date->format('d.m.Y') }}
                            · {{ $booking->guests_count }} гостей
                        </div>
                        <div class="text-sm text-slate-500">
                            Статус:
                            <span class="font-medium
                                @if($booking->status === 'confirmed') text-green-600
                                @elseif($booking->status === 'pending') text-yellow-600
                                @else text-red-600 @endif">
                                @if($booking->status === 'confirmed') Подтверждено
                                @elseif($booking->status === 'pending') Ожидание
                                @else Отменено @endif
                            </span>
                            · Оплата:
                            <span class="font-medium
                                @if($booking->payment_status === 'paid') text-green-600
                                @elseif($booking->payment_status === 'pending') text-yellow-600
                                @else text-slate-600 @endif">
                                @if($booking->payment_status === 'paid') Оплачено
                                @elseif($booking->payment_status === 'pending') Ожидает оплаты
                                @else {{ $booking->payment_status ?? '—' }} @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <div class="text-xs text-slate-400">
                            Создано: {{ $booking->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="text-right">
                            @if($booking->total_amount)
                                <div class="text-xs text-slate-500">Итого к оплате</div>
                                <div class="text-2xl font-bold text-sky-600">
                                    {{ number_format($booking->total_amount, 0, ',', ' ') }} ₽
                                </div>
                            @endif
                        </div>
                        @if($booking->house)
                            <a href="{{ route('houses.show', $booking->house) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-sky-600 hover:text-sky-700">
                                Перейти к домику
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

