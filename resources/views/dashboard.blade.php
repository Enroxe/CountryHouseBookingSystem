@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
    <div class="bg-gradient-to-b from-sky-100 to-white min-h-[calc(100vh-80px)] py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-slate-900">Личный кабинет</h1>
                <p class="text-slate-600 mt-1">Добро пожаловать, {{ Auth::user()->name }}!</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-4">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-slate-100">
                        <h2 class="text-xl font-semibold text-slate-900 mb-3">Быстрые действия</h2>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('houses.index') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-600 text-white text-sm font-semibold shadow hover:bg-sky-700 transition">
                                Перейти к домикам
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('bookings.my') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-sky-700 border border-sky-200 text-sm font-semibold shadow-sm hover:bg-sky-50 transition">
                                Мои бронирования
                            </a>
                            <a href="{{ route('profile.edit') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-slate-700 border border-slate-200 text-sm font-semibold shadow-sm hover:bg-slate-50 transition">
                                Настройки профиля
                            </a>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-slate-100">
                        <h2 class="text-sm font-semibold text-slate-500 mb-2">Аккаунт</h2>
                        <p class="text-slate-900 font-medium">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-slate-500 mt-1">
                            Зарегистрирован: {{ Auth::user()->created_at->format('d.m.Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
