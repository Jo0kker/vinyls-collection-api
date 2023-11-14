<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Search extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'vinyl_id',
        'format_vinyl_id',
        'user_id'
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
     * Get the search.
     *
     * @return BelongsTo<FormatVinyl>
     */
    public function format(): BelongsTo
    {
        return $this->belongsTo(FormatVinyl::class, 'format_vinyl_id');
    }
}
