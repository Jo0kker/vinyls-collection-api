<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CollectionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function commonRules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('collections')->where(function ($query) {
                    return $query->where('user_id', auth()->id())
                        ->whereNull('deleted_at');
                }),
            ],
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'You already have a collection with this name.',
        ];
    }
}
