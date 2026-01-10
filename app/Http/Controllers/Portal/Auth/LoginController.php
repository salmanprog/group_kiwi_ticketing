<?php

namespace App\Http\Controllers\Portal\Auth;

use App\Http\Controllers\Controller;
use App\Models\{User,CompanyUser};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // if( $request['auth_token'] != config('constants.ADMIN_AUTH_TOKEN') )
        //     return abort(404);

        if( $request->isMethod('post') )
            return self::_login($request);

        return $this->__cbAdminView('auth.login');
    }

    private function _login($request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

          $credentials = [
            'email'     => $request['email'],
            'password'  => $request['password'] ,
        ];
        if( Auth::attempt($credentials) ) {
            $user = User::getAuthUser();
            $getCompany = CompanyUser::getCompany($user->id);
            
            if($user->status =="0"){
                Auth::logout();
                return redirect()->back()->with('error','Your account has been disabled');
            }else{
                if($user->user_type == "admin"){
                    
                    return redirect()->route('admin.dashboard');

                }else{

                    if(isset($getCompany->status) && $getCompany->status == "0"){
                        Auth::logout();
                        return redirect()->back()->with('error','Company account has been disabled');
                    }

                    if($user->user_type == "manager"){
                        return redirect()->route('manager.dashboard');
                    }elseif($user->user_type == "company"){
                        return redirect()->route('company.dashboard');
                    }
                    elseif($user->user_type == "salesman"){
                        return redirect()->route('salesman.dashboard');
                    }
                    elseif($user->user_type == "client"){
                        return redirect()->route('client.dashboard');
                    }
                   
                }
            }
        }
        else {
            return redirect()->back()->with('error','Invalid credential');

        }

    }
}
