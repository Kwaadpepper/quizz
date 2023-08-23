<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @property string $name The name of the type.
 *
 * @package App\Models
 */
class NameType extends Model
{
    use HasFactory;

    // * RELATIONS

    /**
     * Get the gameMode that owns the NameType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameMode(): BelongsTo
    {
        return $this->belongsTo(GameMode::class);
    }

    /**
     * Get all of the names for the NameType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function names(): HasMany
    {
        return $this->hasMany(Name::class);
    }
}
