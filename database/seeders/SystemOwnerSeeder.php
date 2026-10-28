<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SystemOwnerSeeder extends Seeder
{
    /**
     * Asigna el rol super_admin al correo propietario de la instalación. No
     * crea cuentas ni contraseñas: si el usuario aún no existe, lo informa.
     */
    public function run(): void
    {
        $email = trim((string) config('system.owner_email'));

        if ($email === '') {
            return;
        }

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            $this->command?->warn("No existe un usuario con el correo {$email}; no se asignó super_admin.");

            return;
        }

        if (! $user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }
    }
}
