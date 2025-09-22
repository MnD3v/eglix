<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('documents.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        return $user->hasPermission('documents.view') && 
               $user->church_id === $document->church_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('documents.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        return $user->hasPermission('documents.edit') && 
               $user->church_id === $document->church_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->hasPermission('documents.delete') && 
               $user->church_id === $document->church_id;
    }

    /**
     * Determine whether the user can download the model.
     */
    public function download(User $user, Document $document): bool
    {
        return $user->hasPermission('documents.download') && 
               $user->church_id === $document->church_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->hasPermission('documents.edit') && 
               $user->church_id === $document->church_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->hasPermission('documents.delete') && 
               $user->church_id === $document->church_id;
    }
}