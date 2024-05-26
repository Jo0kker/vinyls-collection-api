<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filepond extends Model
{
    use HasFactory;

    protected $table = 'fileponds';

    public function creator()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }
}
