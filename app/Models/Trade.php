<?php

namespace App\Models;

use App\Traits\HasUniqueVinyls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Trade extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasUniqueVinyls;

    protected $fillable = [
        'description',
        'vinyl_id',
        'format',
        'user_id',
    ];

    /**
     * Get the user.
     *
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vinyl.
     *
     * @return BelongsTo<Vinyl>
     */
    public function vinyl(): BelongsTo
    {
        return $this->belongsTo(Vinyl::class);
    }

    /**
     * Scope a query to order by vinyl title.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByVinylTitle($query)
    {
        return $query->join('vinyls', 'trades.vinyl_id', '=', 'vinyls.id')
            ->reorder()
            ->orderBy('vinyls.title', 'asc')
            ->select('trades.*');
    }
}
