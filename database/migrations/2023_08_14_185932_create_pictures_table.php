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
         * appartient 1 question
         * appartient 1 pictureType
         */
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();

            $table->string('picture')->comment('The picture path using storage strategy');
            $table->string('comment')->nullable()->comment('A small comment of the picture');

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
        Schema::dropIfExists('pictures');
    }
};
