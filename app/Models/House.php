<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price_per_night',
        'max_guests',
        'image_url',
        'location',
        'discount_percent',
        'discount_amount',
        'promotion_text',
        'status',
        'promotion_start',
        'promotion_end',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'promotion_start' => 'date',
        'promotion_end' => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Вычисляет финальную цену с учетом скидки
     */
    public function getFinalPriceAttribute()
    {
        $price = $this->price_per_night;

        if ($this->hasActiveDiscount()) {
            if ($this->discount_percent) {
                $price = $price * (1 - $this->discount_percent / 100);
            } elseif ($this->discount_amount) {
                $price = max(0, $price - $this->discount_amount);
            }
        }

        return round($price, 2);
    }

    /**
     * Проверяет, активна ли скидка/акция
     */
    public function hasActiveDiscount()
    {
        if (!$this->discount_percent && !$this->discount_amount) {
            return false;
        }

        $now = now()->startOfDay();

        if ($this->promotion_start && $now->lt($this->promotion_start)) {
            return false;
        }

        if ($this->promotion_end && $now->gt($this->promotion_end)) {
            return false;
        }

        return true;
    }

    /**
     * Получает размер скидки в процентах или рублях
     */
    public function getDiscountDisplayAttribute()
    {
        if (!$this->hasActiveDiscount()) {
            return null;
        }

        if ($this->discount_percent) {
            return '-' . number_format($this->discount_percent, 0) . '%';
        } elseif ($this->discount_amount) {
            return '-' . number_format($this->discount_amount, 0, ',', ' ') . ' ₽';
        }

        return null;
    }
}
