<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Correo del propietario del sistema
    |--------------------------------------------------------------------------
    |
    | Al instalar la aplicación, el seeder asigna el rol super_admin a este
    | correo si la cuenta ya existe. Cada empresa puede cambiarlo en su .env.
    |
    */

    'owner_email' => env('SYSTEM_OWNER_EMAIL', 'ivanruizdelfin@gmail.com'),

];
