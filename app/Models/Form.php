<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Form extends Model
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function sharedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'form_shares')
            ->withTimestamps();
    }

    public function isAccessibleBy(?User $user): bool
    {
        if ($this->is_public) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $this->user_id === $user->id || 
               $this->sharedUsers()
                    ->select('users.id')
                    ->where('users.id', $user->id)
                    ->exists();
    }

    public function exports()
    {
        return $this->hasMany(FormExport::class);
    }

    public function duplicate()
    {
        DB::beginTransaction();
        
        try {
            // Копируем основные данные формы
            $newForm = $this->replicate();
            $newForm->name = $this->name . ' (Копия)';
            $newForm->save();

            // Копируем поля
            foreach ($this->fields as $field) {
                $newField = $field->replicate();
                $newField->form_id = $newForm->id;
                $newField->save();
            }

            DB::commit();
            return $newForm;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
} 