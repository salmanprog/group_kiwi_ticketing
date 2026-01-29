<?php

namespace App\Http\Controllers\Portal\Auth;

use App\Http\Controllers\Controller;
//use Auth0\Laravel\Facade\Auth0;
use Auth0\SDK\Auth0;

class Auth0LoginController extends Controller
{
    public function login()
    {
        $state = csrf_token();
    session(['auth0_state' => $state]);

    $query = http_build_query([
        'client_id' => env('AUTH0_CLIENT_ID'),
        'redirect_uri' => env('AUTH0_REDIRECT_URI'),
        'response_type' => 'code',
        'scope' => env('AUTH0_SCOPE', 'openid profile email'),
        'state' => $state,
    ]);

    return redirect('https://' . env('AUTH0_DOMAIN') . '/authorize?' . $query);
    }

    public function callback()
    {
        // Initialize the SDK as you already have
        $auth0 = new Auth0([
            'domain'        => env('AUTH0_DOMAIN'),
            'clientId'      => env('AUTH0_CLIENT_ID'),
            'clientSecret'  => env('AUTH0_CLIENT_SECRET'),
            'redirectUri'   => env('AUTH0_REDIRECT_URI'),
            'cookieSecret'  => substr(base64_decode(str_replace('base64:', '', env('APP_KEY'))), 0, 32),
            'strategy'      => 'webapp',
        ]);

        try {
            $auth0->exchange();
            $user = $auth0->getUser();
            // Optional: Get full credentials (tokens + user)
            // $credentials = $auth0->getCredentials();

            dd($user); 
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function logout()
    {
        // Log out Laravel user
        auth()->logout();

        // Redirect to Auth0 logout
        return Auth0::logout(
            returnTo: route('portal.login')
        );
    }
}
