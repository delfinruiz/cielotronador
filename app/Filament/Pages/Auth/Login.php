<?php

namespace App\Filament\Pages\Auth;

use Illuminate\Contracts\Support\Htmlable;

class Login extends \Filament\Auth\Pages\Login
{
    protected string $view = 'filament.auth.login';

    public function getHeading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getHeading();
        }

        return 'Ingrese a su cuenta';
    }
}
