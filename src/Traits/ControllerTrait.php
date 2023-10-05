<?php

namespace Novay\SSOKaltim\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

trait ControllerTrait
{
    public function redirect()
    {
        $queries = http_build_query([
            'client_id' => config('sso-kaltim.oauth_server.client_id'),
            'redirect_uri' => config('sso-kaltim.oauth_server.redirect'),
            'response_type' => 'code', 
            'prompt' => 'consent'
        ]);

        return redirect(config('sso-kaltim.oauth_server.uri') . '/oauth/authorize?' . $queries);
    }

    public function callback(Request $request)
    {
        $response = Http::withoutVerifying()->post(config('sso-kaltim.oauth_server.uri') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('sso-kaltim.oauth_server.client_id'),
            'client_secret' => config('sso-kaltim.oauth_server.client_secret'),
            'redirect_uri' => config('sso-kaltim.oauth_server.redirect'),
            'code' => $request->code
        ]);

        if($request->filled('error')):
            return response()->json([
                'error' => $request->error, 
                'error_description' => $request->error_description, 
            ]);
        endif;

        $response = $response->json();
        if(!isset($response['access_token'])):
            return redirect('/');
        endif;

        $user = Http::withToken($response['access_token'])->acceptJson()
            ->withoutVerifying()
            ->get(config('sso-kaltim.oauth_server.uri') . '/api/user');
        
        $user = $user->json();

        return $this->handle($response, $user);
    }

    public function refresh(Request $request)
    {
        $response = Http::post(config('sso-kaltim.oauth_server.uri') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->user()->token_sso->refresh_token,
            'client_id' => config('sso-kaltim.oauth_server.client_id'),
            'client_secret' => config('sso-kaltim.oauth_server.client_secret'),
            'redirect_uri' => config('sso-kaltim.oauth_server.redirect')
        ]);

        if ($response->status() !== 200) {
            $request->user()->token_sso()->delete();

            return redirect('/')
                ->withStatus('Authorization failed from OAuth server.');
        }

        $response = $response->json();
        $request->user()->token_sso()->update([
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']
        ]);

        return redirect('/');
    }
}