<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PolicyController extends Controller
{
    public function privacidad(): View
    {
        return view('politicas.privacidad');
    }

    public function cookies(): View
    {
        return view('politicas.cookies');
    }

    public function datos(): View
    {
        return view('politicas.datos');
    }
}
