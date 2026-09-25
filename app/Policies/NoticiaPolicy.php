<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Noticia;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class NoticiaPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Noticia');
    }

    public function view(AuthUser $authUser, Noticia $noticia): bool
    {
        return $authUser->can('View:Noticia');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Noticia');
    }

    public function update(AuthUser $authUser, Noticia $noticia): bool
    {
        return $authUser->can('Update:Noticia');
    }

    public function delete(AuthUser $authUser, Noticia $noticia): bool
    {
        return $authUser->can('Delete:Noticia');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Noticia');
    }

    public function restore(AuthUser $authUser, Noticia $noticia): bool
    {
        return $authUser->can('Restore:Noticia');
    }

    public function forceDelete(AuthUser $authUser, Noticia $noticia): bool
    {
        return $authUser->can('ForceDelete:Noticia');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Noticia');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Noticia');
    }

    public function replicate(AuthUser $authUser, Noticia $noticia): bool
    {
        return $authUser->can('Replicate:Noticia');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Noticia');
    }
}
