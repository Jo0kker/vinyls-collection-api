<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionVinylMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_vinyl_id',
        'type',
        'url',
        'title',
    ];

    public function collectionVinyl(): BelongsTo
    {
        return $this->belongsTo(CollectionVinyl::class);
    }
}
