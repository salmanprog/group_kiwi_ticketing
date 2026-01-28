<?php

namespace App\Http\Controllers\Portal\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth0\Laravel\Facade\Auth0;

class Auth0LoginController extends Controller
{
    public function login()
    {
        $query = http_build_query([
            'client_id' => env('AUTH0_CLIENT_ID'),
            'redirect_uri' => env('AUTH0_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => env('AUTH0_SCOPE', 'openid profile email'),
        ]);

        return redirect('https://' . env('AUTH0_DOMAIN') . '/authorize?' . $query);
    }

    public function callback()
    {
        $user = Auth0::getUser();

        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Login failed.');
        }

        auth()->loginUsingId($user['sub']); // optional

        // Debug user
        dd($user);

        return redirect()->intended('/portal/dashboard');
    }

    public function logout()
    {
        auth()->logout();

        $returnTo = urlencode(env('APP_URL') . '/portal/login');

        return redirect('https://' . env('AUTH0_DOMAIN') . '/v2/logout?client_id='
            . env('AUTH0_CLIENT_ID') . '&returnTo=' . $returnTo);
    }
}
