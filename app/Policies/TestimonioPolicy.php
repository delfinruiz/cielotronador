<?php

namespace App\Policies;

use App\Models\Testimonio;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TestimonioPolicy
{
    use HandlesAuthorization;

    public function before(AuthUser $authUser): ?bool
    {
        return $authUser->hasRole('super_admin') ? true : null;
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Testimonio');
    }

    public function view(AuthUser $authUser, Testimonio $testimonio): bool
    {
        return $authUser->can('View:Testimonio');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Testimonio');
    }

    public function update(AuthUser $authUser, Testimonio $testimonio): bool
    {
        return $authUser->can('Update:Testimonio');
    }

    public function delete(AuthUser $authUser, Testimonio $testimonio): bool
    {
        return $authUser->can('Delete:Testimonio');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Testimonio');
    }

    public function restore(AuthUser $authUser, Testimonio $testimonio): bool
    {
        return $authUser->can('Restore:Testimonio');
    }

    public function forceDelete(AuthUser $authUser, Testimonio $testimonio): bool
    {
        return $authUser->can('ForceDelete:Testimonio');
    }
}
