<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return HasMany<CollectionVinyl>
     */
    public function collectionVinyls(): HasMany
    {
        return $this->hasMany(CollectionVinyl::class);
    }

    /**
     * Get the user.
     *
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vinyls(): HasManyThrough
    {
        return $this->hasManyThrough(Vinyl::class, CollectionVinyl::class, 'collection_id', 'id', 'id', 'vinyl_id');
    }
}
