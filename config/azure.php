<?php

return [
    'appId'             => env('AZURE_CLIENT_ID'),
    'appSecret'         => env('AZURE_CLIENT_SECRET'),
    'redirectUri'       => env('AZURE_REDIRECT_URI'),
    'authority'         => env('AZURE_AUTHORITY', 'https://login.microsoftonline.com/common'),
    'authorizeEndpoint' => '/oauth2/v2.0/authorize',
    'tokenEndpoint'     => '/oauth2/v2.0/token',
    'scopes'            => env('AZURE_SCOPES', 'openid profile offline_access user.read'),
    'admin_auth'        => env('ADMIN_AUTH'),
];
