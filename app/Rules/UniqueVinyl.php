<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueVinyl implements ValidationRule
{
    public function __construct(
        private readonly array $attributes,
        private readonly string $table
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userId = auth()->user()->id;
        $exists = DB::table($this->table)
            ->where('user_id', $userId)
            ->where('vinyl_id', $value)
            ->where('format', $this->attributes['format'])
            ->exists();

        if ($exists) {
            $fail('The vinyl already exists in this collection.');
        }
    }
}
