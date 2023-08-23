<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * @property string $name The actual name.
 *
 * @package App\Models
 */
class Name extends Model
{
    use HasFactory;

    // * RELATIONS

    /**
     * Get the gameMode that owns the Name
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameMode(): BelongsTo
    {
        return $this->belongsTo(GameMode::class);
    }

    /**
     * Get the nameType that owns the Name
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nameType(): BelongsTo
    {
        return $this->belongsTo(NameType::class);
    }
}
