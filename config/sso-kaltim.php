<?php 

return [

    'oauth_server' => [

        'client_id' => env('OAUTH_SERVER_ID'),

        'client_secret' => env('OAUTH_SERVER_SECRET'),

        'redirect' => env('OAUTH_SERVER_REDIRECT_URI'),

        'uri' => env('OAUTH_SERVER_URI'),
        
    ]

];