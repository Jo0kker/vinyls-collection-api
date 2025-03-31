<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vinyl extends Model
{
    use HasFactory, SoftDeletes;

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
     * Get the collections.
     *
     * @return BelongsToMany<Collection>
     */
    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_vinyls')
            ->withPivot(['user_id'])
            ->withTimestamps();
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

    /**
     * Scope a query to find users who own this vinyl.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedByUsers($query)
    {
        return $query->select('vinyls.*')
            ->selectRaw('(
                SELECT json_agg(json_build_object(
                    \'id\', u.id,
                    \'name\', u.name,
                    \'email\', u.email,
                    \'first_name\', u.first_name,
                    \'last_name\', u.last_name,
                    \'avatar\', u.avatar
                ))
                FROM collection_vinyls cv
                JOIN collections c ON cv.collection_id = c.id
                JOIN users u ON c.user_id = u.id
                WHERE cv.vinyl_id = vinyls.id
            ) as owners')
            ->orderBy('vinyls.id', 'desc');
    }
}
