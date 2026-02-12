<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protects routes for Laravel client login (email/password).
 * Uses auth guard "web"; redirects unauthenticated users to client.login.
 * Use this for routes that are accessed after /portal/client/login (not Auth0).
 */
class AuthClient
{
    public function __construct(protected Auth $auth) {}

    public function handle(Request $request, Closure $next, string $guard = 'web'): Response
    {
        if ($this->auth->guard($guard)->guest()) {
            return redirect()->guest(route('client.login'));
        }

        return $next($request);
    }
}
