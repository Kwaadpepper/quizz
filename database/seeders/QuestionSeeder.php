<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GameMode;
use App\Models\Name;
use App\Models\Question;
use App\Models\Tag;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
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
            $tags       = Tag::query()->whereHas('gameMode', fn (Builder $q) => $q->where('id', $mode->id))->get();
            $names      = Name::query()->whereHas('gameMode', fn (Builder $q) => $q->where('id', $mode->id))->get();
            $categories = Category::query()->whereHas('gameMode', fn (Builder $q) => $q->where('id', $mode->id))->get();
            Question::factory(140)->make()
                ->each(fn (Question $question) => $question->gameMode()->associate($mode))
                ->each(fn (Question $question) => $question->category()->associate($categories->random()))
                ->each(fn (Question $question) => $question->saveQuietly())
                ->each(fn (Question $question) => $question->tags()->sync($tags->random(rand(0, 3))))
                ->each(fn (Question $question) => $names->random(rand(1, 2))
                    ->each(fn (Name $name) => $question->names()
                        ->attach([$name->id => ['name_type_id' => $name->name_type_id]])));
        });
    }
}
