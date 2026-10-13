<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Initial Director Account
    |--------------------------------------------------------------------------
    |
    | Used by DirectorSeeder to create the single HDC Director account.
    | Set these in .env; they are never committed.
    |
    */

    'director' => [
        'email' => env('DIRECTOR_EMAIL'),
        'password' => env('DIRECTOR_PASSWORD'),
        'first_name' => env('DIRECTOR_FIRST_NAME', 'HDC'),
        'last_name' => env('DIRECTOR_LAST_NAME', 'Director'),
    ],

];
