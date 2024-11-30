<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'discogs_folder_id',
        'user_id'
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

    protected function vinylsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->collectionVinyls()->count()
        );
    }

    protected $appends = ['vinyls_count'];

    public function scopeOrderByVinylsCount($query, $direction = 'desc')
    {
        return $query->withCount('collectionVinyls')
        ->reorder()
                     ->orderBy('collection_vinyls_count', $direction);

        // $query->dd();
    }
}
