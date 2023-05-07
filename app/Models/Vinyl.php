<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vinyl extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'genre',
        'image',
        'track_list',
        'released',
        'provenance',
        'discog_id',
        'discog_url',
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
     * Get the format.
     *
     * @return BelongsTo<FormatVinyls>
     */
    public function format(): BelongsTo
    {
        return $this->belongsTo(FormatVinyls::class);
    }
}
