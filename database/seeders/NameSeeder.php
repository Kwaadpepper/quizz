<?php

namespace Database\Seeders;

use App\Models\GameMode;
use App\Models\Name;
use App\Models\NameType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NameSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        GameMode::all()->each(function (GameMode $mode) {
            NameType::query()->whereHas('gameMode', fn (Builder $q) => $q->where('id', $mode->id))->get()
                ->each(function (NameType $type) use ($mode) {
                    Name::factory(rand(20, 30))->make()
                        ->each(fn (Name $name) => $name->gameMode()->associate($mode))
                        ->each(fn (Name $name) => $name->nameType()->associate($type))
                        ->each(fn (Name $name) => $name->saveQuietly());
                });
        });
    }
}
