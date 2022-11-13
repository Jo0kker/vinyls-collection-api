<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasPermissions;

class Vinyl extends Model
{
    use HasFactory;

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
