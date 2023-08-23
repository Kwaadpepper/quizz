<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 *
 * @property string $name The team name
 *
 * @package App\Models
 */
class Team extends Model
{
    use HasFactory;

    // TODO: proposer d'associer une liste déroulante autocomplete de classes

    // * RELATIONS

    /**
     * The quizzs that belong to the Quizz
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quizzs(): BelongsToMany
    {
        return $this->belongsToMany(Quizz::class);
    }
}
