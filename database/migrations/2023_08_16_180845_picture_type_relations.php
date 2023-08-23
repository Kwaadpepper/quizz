<?php

use App\Lib\Utils;
use App\Models\GameMode;
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
        Schema::table('picture_types', function (Blueprint $table) {
            Utils::createForeignKeyFor(GameMode::class, $table, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('picture_types', function (Blueprint $table) {
            Utils::dropForeignKeyOf(GameMode::class, $table);
        });
    }
};
