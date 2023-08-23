<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 *
 * @property string $phrase The question phrase eg: identify this.
 *
 * @package App\Models
 */
class Question extends Model
{
    use HasFactory;

    // TODO: ajouter le systeme de tag partagé dans tout le gamemode

    // * RELATIONS

    /**
     * Get the gameMode that owns the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameMode(): BelongsTo
    {
        return $this->belongsTo(GameMode::class);
    }

    /**
     * The category that belong to the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The quizzs that belong to the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quizzs(): BelongsToMany
    {
        return $this->belongsToMany(Quizz::class);
    }

    /**
     * Get all of the names for the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function names(): BelongsToMany
    {
        return $this->belongsToMany(Name::class, 'question_name');
    }

    /**
     * Get all of the tags for the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get all of the pictures for the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pictures(): BelongsToMany
    {
        return $this->belongsToMany(Picture::class);
    }
}
