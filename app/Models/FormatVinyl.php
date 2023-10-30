<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormatVinyl extends Model
{
    use HasFactory;

    /**
     * Get the vinyls.
     */
    public function vinyls(): HasMany
    {
        return $this->hasMany(Vinyl::class);
    }
}
