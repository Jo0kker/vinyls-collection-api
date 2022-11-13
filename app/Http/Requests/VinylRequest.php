<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;

class VinylRequest extends Request
{
    public function commonRules() : array
    {
        return [];
    }

    public function storeRules() : array
    {
        return [

        ];
    }
}
