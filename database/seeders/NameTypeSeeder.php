<?php

namespace Database\Seeders;

use App\Models\GameMode;
use App\Models\NameType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NameTypeSeeder extends Seeder
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
            NameType::factory(\rand(1, 2))->make()
                ->each(fn (NameType $type) => $type->gameMode()->associate($gameMode))
                ->each(fn (NameType $type) => $type->saveQuietly());
        });
    }
}
