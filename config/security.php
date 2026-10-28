<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Segundo factor (TOTP)
    |--------------------------------------------------------------------------
    |
    | Desactivado por ahora. Al poner SECURITY_MFA_REQUIRED=true, Filament
    | exigirá que cada usuario configure una app autenticadora al entrar.
    |
    */

    'mfa_required' => env('SECURITY_MFA_REQUIRED', false),

];
