<?php

namespace App\Http\Controllers\Portal\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginChoiceController extends Controller
{
    /**
     * Show the login method choice page (Laravel session vs Auth0).
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return $this->__cbAdminView('auth.login-choose');
    }
}
