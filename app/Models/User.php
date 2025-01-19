<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function sharedForms()
    {
        return $this->belongsToMany(Form::class, 'form_shares')
            ->withTimestamps();
    }

    public function dictionaries()
    {
        return $this->hasMany(Dictionary::class);
    }
}
