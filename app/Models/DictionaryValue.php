<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DictionaryValue extends Model
{
    protected $fillable = [
        'value',
        'sort_order'
    ];

    public function dictionary(): BelongsTo
    {
        return $this->belongsTo(Dictionary::class);
    }
} 