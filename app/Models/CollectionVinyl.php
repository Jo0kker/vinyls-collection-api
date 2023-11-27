<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CollectionVinyl extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'vinyl_id',
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
}
