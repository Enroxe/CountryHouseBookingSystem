<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-black">Управление бронированиями</h1>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-600">
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Пользователь</th>
                        <th class="px-6 py-4 font-semibold">Дом</th>
                        <th class="px-6 py-4 font-semibold">Период</th>
                        <th class="px-6 py-4 font-semibold">Гостей</th>
                        <th class="px-6 py-4 font-semibold">Сумма</th>
                        <th class="px-6 py-4 font-semibold">Статус</th>
                        <th class="px-6 py-4 font-semibold">Оплата</th>
                        <th class="px-6 py-4 font-semibold text-right">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">{{ $booking->id }}</td>
                            <td class="px-6 py-4 font-medium">{{ $booking->user->name }}</td>
                            <td class="px-6 py-4">{{ $booking->house->title }}</td>
                            <td class="px-6 py-4">
                                {{ $booking->start_date->format('d.m.Y') }} –
                                {{ $booking->end_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4">{{ $booking->guests_count }}</td>
                            <td class="px-6 py-4 font-semibold">
                                @if($booking->total_amount)
                                    {{ number_format($booking->total_amount, 0, ',', ' ') }} ₽
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    @if($booking->status === 'pending') Ожидание
                                    @elseif($booking->status === 'confirmed') Подтверждено
                                    @else Отменено
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->payment_status)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($booking->payment_status === 'paid') bg-green-100 text-green-700
                                        @elseif($booking->payment_status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($booking->payment_status === 'failed') bg-red-100 text-red-700
                                        @else bg-slate-100 text-slate-700 @endif">
                                        @if($booking->payment_status === 'paid') Оплачено
                                        @elseif($booking->payment_status === 'pending') Ожидает оплаты
                                        @elseif($booking->payment_status === 'failed') Ошибка оплаты
                                        @else {{ $booking->payment_status }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($booking->status !== 'confirmed')
                                    <button wire:click="setStatus({{ $booking->id }}, 'confirmed')"
                                            class="text-green-600 hover:text-green-700 hover:underline text-xs font-medium">
                                        Подтвердить
                                    </button>
                                @endif
                                @if($booking->status !== 'cancelled')
                                    <button wire:click="setStatus({{ $booking->id }}, 'cancelled')"
                                            class="text-red-600 hover:text-red-700 hover:underline text-xs font-medium">
                                        Отклонить
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
