<?php

namespace Database\Seeders;

use App\Models\GameMode;
use App\Models\Question;
use App\Models\Quizz;
use App\Models\Team;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizzSeeder extends Seeder
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
            $teams     = Team::all();
            $questions = Question::query()->whereHas('gameMode', fn (Builder $q) => $q->where('id', $mode->id))->get();
            Quizz::factory(25)->make()
                ->each(fn (Quizz $quizz) => $quizz->gameMode()->associate($mode))
                ->each(fn (Quizz $quizz) => $quizz->saveQuietly())
                ->each(fn (Quizz $quizz) => $quizz->teams()->sync($teams->random(2)))
                ->each(fn (Quizz $quizz) => $quizz->questions()->sync($questions->random(rand(10, 20))));
        });
    }
}
