<?php

use App\Lib\Utils;
use App\Models\Category;
use App\Models\GameMode;
use App\Models\Name;
use App\Models\NameType;
use App\Models\Picture;
use App\Models\Question;
use App\Models\Quizz;
use App\Models\Tag;
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
        Schema::table('questions', function (Blueprint $table) {
            Utils::createForeignKeyFor(GameMode::class, $table, true);
            Utils::createForeignKeyFor(Category::class, $table, true);
        });
        Schema::create('question_quizz', function (Blueprint $table) {
            Utils::createForeignKeyFor(Question::class, $table, true);
            Utils::createForeignKeyFor(Quizz::class, $table, true);
        });
        Schema::create('question_tag', function (Blueprint $table) {
            Utils::createForeignKeyFor(Question::class, $table, true);
            Utils::createForeignKeyFor(Tag::class, $table, true);
        });
        Schema::create('question_picture', function (Blueprint $table) {
            Utils::createForeignKeyFor(Question::class, $table, true);
            Utils::createForeignKeyFor(Picture::class, $table, true);
        });
        Schema::create('question_name', function (Blueprint $table) {
            Utils::createForeignKeyFor(Question::class, $table, true);
            Utils::createForeignKeyFor(Name::class, $table, true);
            Utils::createForeignKeyFor(NameType::class, $table, true);
            $table->unique([
                (new Question())->getForeignKey(),
                (new Name())->getForeignKey(),
                (new NameType())->getForeignKey(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            Utils::dropForeignKeyOf(GameMode::class, $table);
            Utils::dropForeignKeyOf(Question::class, $table);
        });
        Schema::table('question_tag', function (Blueprint $table) {
            Utils::dropForeignKeyOf(Question::class, $table);
            Utils::dropForeignKeyOf(Tag::class, $table);
        });
        Schema::drop('question_tag');
        Schema::table('question_picture', function (Blueprint $table) {
            Utils::dropForeignKeyOf(Question::class, $table);
            Utils::dropForeignKeyOf(Picture::class, $table);
        });
        Schema::drop('question_picture');
        Schema::table('question_quizz', function (Blueprint $table) {
            Utils::dropForeignKeyOf(Question::class, $table);
            Utils::dropForeignKeyOf(Quizz::class, $table);
        });
        Schema::drop('question_quizz');
        Schema::table('question_name', function (Blueprint $table) {
            Utils::dropForeignKeyOf(Question::class, $table);
            Utils::dropForeignKeyOf(Name::class, $table);
            Utils::dropForeignKeyOf(NameType::class, $table);
            $table->dropUnique([
                (new Question())->getForeignKey(),
                (new Name())->getForeignKey(),
                (new NameType())->getForeignKey(),
            ]);
        });
        Schema::drop('question_quizz');
    }
};
