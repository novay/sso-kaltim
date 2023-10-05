<?php 

use Illuminate\Support\Facades\Route;

Route::namespace('Novay\SSOKaltim\Http\Controllers')->prefix('oauth')->as('sso.')->group(function() 
{
    Route::middleware(['web'])->group(function() {
        Route::get('redirect', 'OAuthController@redirect')->name('authorize');
        Route::get('callback', 'OAuthController@callback');
        Route::get('refresh', 'OAuthController@refresh');
    });
});