<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function isAccessibleBy(?User $user): bool
    {
        if ($this->is_public) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $this->user_id === $user->id;
    }

    public function exports()
    {
        return $this->hasMany(FormExport::class);
    }

    public function duplicate()
    {
        DB::beginTransaction();
        
        try {
            $newForm = $this->replicate();
            $newForm->name = $this->name . ' (Копия)';
            $newForm->save();

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