<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionVinyl extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'vinyl_id',
        'format_vinyl_id',
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
     * @return BelongsTo<Vinyl>
     */
    public function vinyl(): BelongsTo
    {
        return $this->belongsTo(Vinyl::class, 'id');
    }

    /**
     * GET format vinyl
     */
    public function format(): BelongsTo
    {
        return $this->belongsTo(FormatVinyl::class, 'format_vinyl_id');
    }
}
