<?php

namespace App\Policies;

use App\Models\Form;
use App\Models\User;

class FormPolicy
{
    public function update(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }

    public function delete(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }

    public function manageFields(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }

    public function viewSubmissions(User $user, Form $form): bool
    {
        return $user->id === $form->user_id || 
               $form->sharedUsers()->where('users.id', $user->id)->exists();
    }
} 