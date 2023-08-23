<?php

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
        /**
         * possede 0-N quizz
         * possede 0-N question
         * possede 0-N category
         * possede 0-N nameType
         * possede 0-N pictureType
         */
        Schema::create('game_modes', function (Blueprint $table) {
            $table->id();

            $table->string('name')->comment('The mode name eg: Reco végétaux, etc');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('game_modes');
    }
};
