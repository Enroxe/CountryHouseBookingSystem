<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\House;

class HouseSeeder extends Seeder
{
    public function run(): void
    {
        $houses = [
            [
                'title' => 'Лесной домик у озера',
                'location' => 'Ленинградская область',
                'description' => 'Уютный домик с камином, баней и видом на озеро.',
                'price_per_night' => 7500,
                'max_guests' => 4,
                'image_url' => '/images/houses/lake-house.jpg',
            ],
            [
                'title' => 'Скандинавский шале',
                'location' => 'Московская область',
                'description' => 'Стильный шале с панорамными окнами и террасой.',
                'price_per_night' => 9500,
                'max_guests' => 6,
                'image_url' => '/images/houses/chale.jpg',
            ],
            [
                'title' => 'Дом у реки',
                'location' => 'Карелия',
                'description' => 'Тихое место для рыбалки и отдыха всей семьёй.',
                'price_per_night' => 6800,
                'max_guests' => 5,
                'image_url' => '/images/houses/house-river.jpg',
            ],
            [
                'title' => 'Вилла программиста',
                'location' => 'Саратовская область',
                'description' => 'Уютное место, чтобы заварить себе раф на кокосовом и насладиться вайб-кодингом на laravel.',
                'price_per_night' => 69000,
                'max_guests' => 1,
                'image_url' => '/images/houses/it-house.jpg',
            ],
        ];

        foreach ($houses as $houseData) {
            $house = House::firstOrCreate(['title' => $houseData['title']], $houseData);
            // Обновляем image_url, если домик уже существует
            if ($house->wasRecentlyCreated === false) {
                $house->update(['image_url' => $houseData['image_url']]);
            }
        }
    }
}
