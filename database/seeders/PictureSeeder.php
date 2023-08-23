<?php

namespace Database\Seeders;

use App\Lib\Helpers\FileStorageHelper;
use App\Models\GameMode;
use App\Models\Picture;
use App\Models\PictureType;
use App\Models\Question;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PictureSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $pictureTypes = PictureType::all();
        GameMode::all()->each(function (GameMode $mode) use ($pictureTypes) {
            Question::query()->whereHas('gameMode', fn (Builder $q) => $q->where('id', $mode->id))
                ->each(function (Question $question) use ($mode, $pictureTypes) {
                    Picture::factory(rand(3, 5))->make()
                        ->each(fn (Picture $pic) => $pic->gameMode()->associate($mode))
                        ->each(fn (Picture $pic) => $pic->pictureType()->associate($pictureTypes->random()))
                        ->each(function (Picture $pic) {
                            $pic->picture = FileStorageHelper::storeFile($pic, new \SplFileInfo(
                                \resource_path('../database/factories/assets/default-picture-600x600.png')
                            ));
                        })->each(fn (Picture $pic) => $pic->saveQuietly())
                        ->each(fn (Picture $pic) => $pic->questions()->attach($question));
                });
        });
    }
}
