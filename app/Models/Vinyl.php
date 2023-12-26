<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vinyl extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'genre',
        'image',
        'track_list',
        'type',
        'released',
        'provenance',
        'discog_id',
        'discog_url',
        'discog_img',
        'discog_videos',
        'collection_id',
    ];

    /**
     * Get the collection.
     *
     * @return BelongsTo<Collection>
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Get the trades.
     *
     * @return HasMany<Trade>
     */
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * Get the searches.
     *
     * @return HasMany<Search>
     */
    public function searches(): HasMany
    {
        return $this->hasMany(Search::class);
    }

    /**
     * Get the traders.
     *
     * @return BelongsToMany<User>
     */
    public function traders(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'trades')->withPivot('description', 'format_vinyl_id');
    }

    /**
     * Get the searchers.
     *
     * @return BelongsToMany<User>
     */
    public function searchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'searches')->withPivot('description', 'format_vinyl_id');
    }
}
