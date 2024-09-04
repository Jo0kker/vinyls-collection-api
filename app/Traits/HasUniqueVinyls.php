<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasUniqueVinyls
{
    /**
     * Scope a query to only include unique vinyls.
     */
    public function scopeUniqueVinyls(Builder $query): Builder
    {
        return $query->whereIn('id', function($subQuery) {
            $subQuery->selectRaw('MAX(id)')
                ->from($this->getTable())
                ->groupBy('vinyl_id');
        });
    }
}
