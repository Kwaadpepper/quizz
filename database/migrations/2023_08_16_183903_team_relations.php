<?php

use App\Lib\Utils;
use App\Models\Quizz;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('quizz_team', function (Blueprint $table) {
            Utils::createForeignKeyFor(Team::class, $table, true);
            Utils::createForeignKeyFor(Quizz::class, $table, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('quizz_team', function (Blueprint $table) {
            Utils::dropForeignKeyOf(Team::class, $table);
            Utils::dropForeignKeyOf(Quizz::class, $table);
        });
        Schema::drop('quizz_team');
    }
};
