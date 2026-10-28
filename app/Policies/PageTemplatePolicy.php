<?php

namespace App\Policies;

use App\Models\PageTemplate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PageTemplatePolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:PageTemplate');
    }

    public function view(User $user, PageTemplate $template): bool
    {
        return $user->can('View:PageTemplate');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:PageTemplate');
    }

    public function update(User $user, PageTemplate $template): bool
    {
        return $user->can('Update:PageTemplate');
    }

    public function delete(User $user, PageTemplate $template): bool
    {
        return $user->can('Delete:PageTemplate');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('DeleteAny:PageTemplate');
    }

    public function restore(User $user, PageTemplate $template): bool
    {
        return $user->can('Restore:PageTemplate');
    }

    public function forceDelete(User $user, PageTemplate $template): bool
    {
        return $user->can('ForceDelete:PageTemplate');
    }

    public function activate(User $user, PageTemplate $template): bool
    {
        return $user->hasRole('super_admin');
    }
}
