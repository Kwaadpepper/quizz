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
         * appartient a 1 gamemode
         * possede 0-N name
         */
        Schema::create('name_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->comment('The name of the type');

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
        Schema::dropIfExists('name_types');
    }
};
