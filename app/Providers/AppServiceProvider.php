<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::share('company', [
            'name'     => 'LSW Cars',
            'tagline'  => 'Ihr Partner für Premium-Fahrzeuge',
            'owner'    => 'Inhaber LSW Cars',
            'street'   => 'Petersbergstr. 101',
            'zip'      => '36100',
            'city'     => 'Petersberg',
            'phone'    => '015567 233437',
            'whatsapp' => '4915567233437',
            'email'    => 'lsw_cars@outlook.de',
            'vat'      => 'DE000000000',
            'hrb'      => '–',
            'court'    => '–',
        ]);
    }
}
