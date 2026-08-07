<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingsResource;

class SettingsController extends Controller
{
    public function index(): SettingsResource
    {
        return new SettingsResource([
            'app_name' => 'Vivi Bugliano',
            'description' => 'Scopri con noi le bellezze del nostro territorio',
            'favicon' => '/favicon.ico',
            'logo' => '/img/logo.svg',
            'contact_email' => 'info@comune.bugliano.bo.it',
            'social' => [
                'twitter' => 'https://twitter.com/vivibugliano',
                'facebook' => 'https://facebook.com/vivibugliano',
                'instagram' => 'https://instagram.com/vivibugliano',
            ],
        ]);
    }
}
