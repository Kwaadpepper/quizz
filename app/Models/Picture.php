<?php

namespace App\Models;

use App\Lib\Helpers\FileStorageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 *
 * @property string $picture The picture path using storage strategy.
 * @property string $comment A small comment of the picture.
 *
 * @package App\Models
 */
class Picture extends Model
{
    use HasFactory;

    // * EVENTS

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (self $model) {
            $model->picture = FileStorageHelper::storeFile($model, $model->picture, true);
        });
        static::updating(function (self $model) {
            $model->picture = FileStorageHelper::storeFile($model, $model->picture, true);
        });
        static::updated(function (self $model) {
            FileStorageHelper::removeOldFile($model, 'picture');
        });
        static::deleted(function (self $model) {
            FileStorageHelper::removeFile($model, 'picture');
        });
    }

    // * RELATIONS

    /**
     * Get the gameMode that owns the Picture
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameMode(): BelongsTo
    {
        return $this->belongsTo(GameMode::class);
    }

    /**
     * Get the pictureType that owns the Picture
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pictureType(): BelongsTo
    {
        return $this->belongsTo(PictureType::class);
    }

    /**
     * Get all of the questions for the Picture
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_picture');
    }
}
