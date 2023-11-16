<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueVinylInCollection implements ValidationRule
{
    public function __construct(private readonly array $attributes)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('collection_vinyls')
            ->where('collection_id', $this->attributes['collection_id'])
            ->where('vinyl_id', $value)
            ->where('format_vinyl_id', $this->attributes['format_vinyl_id'])
            ->exists();
        if ($exists) {
            $fail('The vinyl already exists in this collection.');
        }
    }
}
