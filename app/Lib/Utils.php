<?php

namespace App\Lib;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Utils
{
    /**
     * Create foreign key for a Model
     *
     * @param string                                $modelClass
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @param boolean                               $deleteCascade
     * @return void
     */
    public static function createForeignKeyFor(
        string $modelClass,
        Blueprint $table,
        bool $deleteCascade = false
    ): void {
        $foreignId = $table->foreignIdFor($modelClass);
        if (Schema::hasColumn($table->getTable(), 'id')) {
            $foreignId->after('id');
        }
        $foreign = $table->foreign((new $modelClass())->getForeignKey())
            ->references((new $modelClass())->getKeyName())
            ->on((new $modelClass())->getTable());
        if ($deleteCascade) {
            $foreign->cascadeOnDelete();
        }
    }

    /**
     * Drop foreign key for a Model
     *
     * @param string                                $modelClass
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return void
     */
    public static function dropForeignKeyOf(string $modelClass, Blueprint $table): void
    {
        $table->dropForeign([(new $modelClass())->getForeignKey()]);
        $table->dropForeignIdFor(GameMode::class);
    }
}
