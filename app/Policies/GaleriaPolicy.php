<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Galeria;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class GaleriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Galeria');
    }

    public function view(AuthUser $authUser, Galeria $galeria): bool
    {
        return $authUser->can('View:Galeria');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Galeria');
    }

    public function update(AuthUser $authUser, Galeria $galeria): bool
    {
        return $authUser->can('Update:Galeria');
    }

    public function delete(AuthUser $authUser, Galeria $galeria): bool
    {
        return $authUser->can('Delete:Galeria');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Galeria');
    }

    public function restore(AuthUser $authUser, Galeria $galeria): bool
    {
        return $authUser->can('Restore:Galeria');
    }

    public function forceDelete(AuthUser $authUser, Galeria $galeria): bool
    {
        return $authUser->can('ForceDelete:Galeria');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Galeria');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Galeria');
    }

    public function replicate(AuthUser $authUser, Galeria $galeria): bool
    {
        return $authUser->can('Replicate:Galeria');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Galeria');
    }
}
