<?php

namespace App\Models;

use App\Traits\HasUniqueVinyls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Search extends Model
{
    use HasFactory, HasUniqueVinyls;

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
}
