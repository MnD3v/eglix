<?php

namespace App\Policies;

use App\Models\DocumentFolder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentFolderPolicy
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
    public function view(User $user, DocumentFolder $documentFolder): bool
    {
        return $user->hasPermission('documents.view') && 
               $user->church_id === $documentFolder->church_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('documents.folders');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DocumentFolder $documentFolder): bool
    {
        return $user->hasPermission('documents.folders') && 
               $user->church_id === $documentFolder->church_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DocumentFolder $documentFolder): bool
    {
        return $user->hasPermission('documents.folders') && 
               $user->church_id === $documentFolder->church_id &&
               $documentFolder->documents()->count() === 0;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DocumentFolder $documentFolder): bool
    {
        return $user->hasPermission('documents.folders') && 
               $user->church_id === $documentFolder->church_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DocumentFolder $documentFolder): bool
    {
        return $user->hasPermission('documents.folders') && 
               $user->church_id === $documentFolder->church_id;
    }
}