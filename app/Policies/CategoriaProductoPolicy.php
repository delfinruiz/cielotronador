<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CategoriaProducto;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CategoriaProductoPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CategoriaProducto');
    }

    public function view(AuthUser $authUser, CategoriaProducto $categoriaProducto): bool
    {
        return $authUser->can('View:CategoriaProducto');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CategoriaProducto');
    }

    public function update(AuthUser $authUser, CategoriaProducto $categoriaProducto): bool
    {
        return $authUser->can('Update:CategoriaProducto');
    }

    public function delete(AuthUser $authUser, CategoriaProducto $categoriaProducto): bool
    {
        return $authUser->can('Delete:CategoriaProducto');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CategoriaProducto');
    }

    public function restore(AuthUser $authUser, CategoriaProducto $categoriaProducto): bool
    {
        return $authUser->can('Restore:CategoriaProducto');
    }

    public function forceDelete(AuthUser $authUser, CategoriaProducto $categoriaProducto): bool
    {
        return $authUser->can('ForceDelete:CategoriaProducto');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CategoriaProducto');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CategoriaProducto');
    }

    public function replicate(AuthUser $authUser, CategoriaProducto $categoriaProducto): bool
    {
        return $authUser->can('Replicate:CategoriaProducto');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CategoriaProducto');
    }
}
