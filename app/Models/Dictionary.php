<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dictionary extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_public',
        'user_id'
    ];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(DictionaryItem::class);
    }

    public function values(): HasMany
    {
        return $this->items();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 