<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin Example',
            'email' => 'admin@example.com',
        ]);

        $this->call(GameModeSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(NameTypeSeeder::class);
        $this->call(NameSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(PictureTypeSeeder::class);
        $this->call(PictureSeeder::class);
        $this->call(QuizzSeeder::class);
    }
}
