<?php

namespace Database\Seeders;

use App\Models\GameMode;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        GameMode::all()->each(function (GameMode $mode) {
            Tag::factory(140)->make()
                ->each(fn (Tag $tag) => $tag->gameMode()->associate($mode))
                ->each(fn (Tag $tag) => $tag->saveQuietly());
        });
    }
}
