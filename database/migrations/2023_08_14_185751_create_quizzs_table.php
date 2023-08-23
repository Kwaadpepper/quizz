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
         * appartient a 1 game_mode
         * possede 1-N Team
         * possede 1-N question
         */
        Schema::create('quizzs', function (Blueprint $table) {
            $table->id();

            $table->string('name')->comment('Quizz name eg: quizz of 2023-04-23');

            $table->boolean('completed')->comment('The quizz has been completed');

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
        Schema::dropIfExists('quizzs');
    }
};
