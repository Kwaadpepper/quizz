<?php

namespace Database\Seeders;

use App\Models\GameMode;
use App\Models\PictureType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PictureTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        GameMode::all()->each(function ($gameMode) {
            PictureType::factory(\rand(6, 9))->make()
                ->each(fn (PictureType $picType) => $picType->gameMode()->associate($gameMode))
                ->each(fn (PictureType $picType) => $picType->saveQuietly());
        });
    }
}
