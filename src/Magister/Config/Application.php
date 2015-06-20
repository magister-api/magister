<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Auto-loaded Service Providers
     |--------------------------------------------------------------------------
     */
    'providers' => [

        'Magister\Services\Exception\ExceptionServiceProvider',
        'Magister\Services\Cookie\CookieServiceProvider',
        'Magister\Services\Http\HttpServiceProvider',
        'Magister\Services\Database\DatabaseServiceProvider',
        'Magister\Services\Auth\AuthServiceProvider'

    ],

    /*
     |--------------------------------------------------------------------------
     | Class Aliases
     |--------------------------------------------------------------------------
     */
    'aliases' => [

        'App'       => 'Magister\Services\Support\Facades\App',
        'Auth'      => 'Magister\Services\Support\Facades\Auth',
        'Config'    => 'Magister\Services\Support\Facades\Config',
        'Cookie'    => 'Magister\Services\Support\Facades\Cookie',
        'DB'        => 'Magister\Services\Support\Facades\DB',
        'Http'      => 'Magister\Services\Support\Facades\Http'

    ]

];