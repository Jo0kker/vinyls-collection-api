<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueVinylInCollection implements ValidationRule
{
    public function __construct(private readonly array $attributes)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('collection_vinyls')
            ->where('collection_id', $this->attributes['collection_id'])
            ->where('vinyl_id', $value)
            ->where('format', $this->attributes['format'])
            ->exists();
        if ($exists) {
            $fail('Le vinyle est déjà présent dans votre collection.');
        }
    }
}
