<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GameMode;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        GameMode::all()->each(function ($gameMode) {
            Category::factory(\rand(2, 8))->make()
                ->each(fn (Category $category) => $category->gameMode()->associate($gameMode))
                ->each(fn (Category $category) => $category->saveQuietly());
        });
    }
}
