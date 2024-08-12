<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Collection extends Model
{
    use HasFactory, Searchable;

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

    public function vinyls(): BelongsToMany
    {
        return $this->belongsToMany(Vinyl::class, 'collection_vinyls')->withPivot('format_vinyl_id');
    }
}
