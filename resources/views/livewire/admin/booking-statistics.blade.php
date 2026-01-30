<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-black">Статистика продаж</h1>
        
        <div class="flex gap-2">
            <select wire:model="period" wire:change="setPeriod" class="border border-slate-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="week">Неделя</option>
                <option value="month">Месяц</option>
                <option value="year">Год</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">Всего бронирований</p>
                    <p class="text-3xl font-bold text-black">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">Подтверждено</p>
                    <p class="text-3xl font-bold text-black">{{ $stats['confirmed_bookings'] }}</p>
                </div>
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">Выручка</p>
                    <p class="text-3xl font-bold text-black">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} ₽</p>
                </div>
                <div class="bg-yellow-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">Средний чек</p>
                    <p class="text-3xl font-bold text-black">{{ number_format($stats['average_booking_value'], 0, ',', ' ') }} ₽</p>
                </div>
                <div class="bg-purple-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-black mb-4">Продажи по датам</h2>
            <div class="space-y-3">
                @forelse($dailyStats as $day)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div>
                            <p class="font-medium text-black">{{ \Carbon\Carbon::parse($day->date)->format('d.m.Y') }}</p>
                            <p class="text-sm text-slate-500">{{ $day->count }} бронирований</p>
                        </div>
                        <p class="text-lg font-bold text-sky-600">{{ number_format($day->revenue, 0, ',', ' ') }} ₽</p>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">Нет данных за выбранный период</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-black mb-4">Популярные домики</h2>
            <div class="space-y-3">
                @forelse($popularHouses as $item)
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                        <div class="flex-1">
                            <p class="font-medium text-black">{{ $item['house']->title ?? 'Удален' }}</p>
                            <p class="text-sm text-slate-500">{{ $item['bookings_count'] }} бронирований</p>
                        </div>
                        <p class="text-lg font-bold text-green-600">{{ number_format($item['total_revenue'], 0, ',', ' ') }} ₽</p>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">Нет данных за выбранный период</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

