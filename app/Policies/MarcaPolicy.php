<?php

namespace App\Policies;

use App\Models\Marca;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MarcaPolicy
{
    use HandlesAuthorization;

    public function before(AuthUser $authUser): ?bool
    {
        return $authUser->hasRole('super_admin') ? true : null;
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Marca');
    }

    public function view(AuthUser $authUser, Marca $marca): bool
    {
        return $authUser->can('View:Marca');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Marca');
    }

    public function update(AuthUser $authUser, Marca $marca): bool
    {
        return $authUser->can('Update:Marca');
    }

    public function delete(AuthUser $authUser, Marca $marca): bool
    {
        return $authUser->can('Delete:Marca');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Marca');
    }

    public function restore(AuthUser $authUser, Marca $marca): bool
    {
        return $authUser->can('Restore:Marca');
    }

    public function forceDelete(AuthUser $authUser, Marca $marca): bool
    {
        return $authUser->can('ForceDelete:Marca');
    }
}
