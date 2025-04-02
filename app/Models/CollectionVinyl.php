<?php

namespace App\Models;

use App\Traits\HasUniqueVinyls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CollectionVinyl extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, HasUniqueVinyls;

    protected $fillable = [
        'collection_id',
        'vinyl_id',
        'user_id',
        'format',
        'cover_state',
        'year_purchased',
        'price',
        'description',
    ];

    /**
     * @return BelongsTo<Collection>
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * @return HasOne<Vinyl>
     */
    public function vinyl(): HasOne
    {
        return $this->hasOne(Vinyl::class, 'id', 'vinyl_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Scope a query to order by vinyl title.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByVinylTitle($query)
    {
        return $query->join('vinyls', 'collection_vinyls.vinyl_id', '=', 'vinyls.id')
            ->reorder()
            ->orderBy('vinyls.title', 'asc')
            ->select('collection_vinyls.*');
    }
}
