<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\House;

class UpdateHouseImages extends Command
{
    protected $signature = 'houses:update-images';
    protected $description = 'Обновить пути к изображениям домиков';

    public function handle()
    {
        $updates = [
            'Лесной домик у озера' => '/houses/lake-house.jpg',
            'Скандинавский шале' => '/houses/chale.jpg',
            'Дом у реки' => '/houses/house-river.jpg',
        ];

        foreach ($updates as $title => $imageUrl) {
            $house = House::where('title', $title)->first();
            if ($house) {
                $house->update(['image_url' => $imageUrl]);
                $this->info("Обновлён домик: {$title}");
            } else {
                $this->warn("Домик не найден: {$title}");
            }
        }

        $this->info('Готово! Все изображения обновлены.');
        return 0;
    }
}

