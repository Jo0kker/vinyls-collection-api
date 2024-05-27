<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeMedia extends Model
{
    use HasFactory;


    protected $fillable = [
        'trade_id',
        'file_id',
        'type',
        'url',
        'title',
    ];

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(Filepond::class);
    }
}
