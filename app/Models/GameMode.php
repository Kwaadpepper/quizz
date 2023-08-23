<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @property string $name The mode name eg: Reco végétaux, etc.
 *
 * @package App\Models
 */
class GameMode extends Model
{
    use HasFactory;

    // * RELATIONS

    /**
     * Get all of the quizzs for the GameMode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quizzs(): HasMany
    {
        return $this->hasMany(Quizz::class);
    }

    /**
     * Get all of the categories for the GameMode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get all of the names for the GameMode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function names(): HasMany
    {
        return $this->hasMany(Name::class);
    }

    /**
     * Get all of the nameTypes for the GameMode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nameTypes(): HasMany
    {
        return $this->hasMany(NameType::class);
    }

    /**
     * Get all of the pictures for the GameMode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pictures(): HasMany
    {
        return $this->hasMany(Picture::class);
    }

    /**
     * Get all of the pictureTypes for the GameMode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pictureTypes(): HasMany
    {
        return $this->hasMany(PictureType::class);
    }
}
