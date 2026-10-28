<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modus
    |--------------------------------------------------------------------------
    |
    | 'nieuwsbrief' laadt grapesjs-preset-newsletter en levert bij het opslaan
    | geinlinede, table-based html op: het enige wat in Outlook en Gmail overeind
    | blijft. 'pagina' is de gewone webmodus.
    |
    | Een model kan dit overschrijven met `grapesjsModus()`.
    |
    */
    'modus' => 'pagina',

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | De middleware waarachter de editor-routes hangen. De bewerkbare modellen zijn
    | bijna altijd van een klant, dus hier hoort minstens 'auth' bij, en in een
    | multitenant-app ook wat de organisatie vastzet.
    |
    | 'domein' is null in een app met een enkele host; zet het in een app met
    | meerdere hosts op de host van het beheerscherm, anders hangt de editor ook aan
    | de publieke hosts.
    |
    */
    'routes' => [
        'domein' => null,
        'prefix' => 'grapesjs',
        'middleware' => ['web', 'auth', 'throttle:60,1'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | De gebouwde editor. Publiceer ze met
    | `php artisan vendor:publish --tag=grapesjs-assets` en ververs ze na een
    | update van het pakket.
    |
    */
    'assets' => [
        'js' => 'vendor/grapesjs/editor.js',
        'css' => 'vendor/grapesjs/editor.css',
    ],

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    |
    | Waar afbeeldingen uit de editor terechtkomen. De schijf moet publiek zijn:
    | de gemaakte html wordt naar buiten gestuurd en moet de afbeeldingen zonder
    | authenticatie kunnen tonen.
    |
    */
    'uploads' => [
        'schijf' => 'plantillas',
        // El disco ya apunta a public/img/plantillas; no agregamos otra carpeta.
        'map' => '',
        'max_kb' => 2048,
    ],

];
