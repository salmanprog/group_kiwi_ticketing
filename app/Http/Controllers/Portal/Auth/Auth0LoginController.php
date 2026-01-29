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
        $auth0 = new Auth0([
        'domain'        => env('AUTH0_DOMAIN'),
        'clientId'      => env('AUTH0_CLIENT_ID'),
        'clientSecret'  => env('AUTH0_CLIENT_SECRET'),
        'redirectUri'   => env('AUTH0_REDIRECT_URI'),
        'cookieSecret'  => substr(base64_decode(str_replace('base64:', '', env('APP_KEY'))), 0, 32),
        'scope'         => explode(' ', env('AUTH0_SCOPE', 'openid profile email')),
        'strategy'      => 'webapp',
    ]);

    $user = $auth0->getUser();

    print_r($user);
    die();

    if (!$user) {
        return redirect('/portal/login')->with('error', 'Login failed.');
    }

    $localUser = \App\Models\User::firstOrCreate(
        ['auth0_id' => $user['sub']],
        ['name' => $user['name'] ?? '', 'email' => $user['email'] ?? '']
    );

    auth()->login($localUser);

    return redirect()->intended('/portal/dashboard');
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
