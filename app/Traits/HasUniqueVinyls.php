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
        return $query->whereIn($this->getTable() . '.id', function ($subQuery) {
            $subQuery->selectRaw('MAX(' . $this->getTable() . '.id)')
                ->from($this->getTable())
                ->whereNull('deleted_at')
                ->groupBy('vinyl_id');
        });
    }
}
