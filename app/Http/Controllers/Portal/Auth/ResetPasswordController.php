<?php

namespace App\Http\Controllers\Portal\Auth;

use App\Http\Controllers\Controller;
use App\Models\ResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request,$token)
    {
        if( $request->isMethod('post') )
            return self::_resetPassword($request,$token);

        $checkRequest = ResetPassword::getRequest($token);
        if( !isset($checkRequest->id) ){
            // $url = route('admin.login') . '?auth_token=zekkmdvhkm';
            $url = route('admin.login');
            return redirect($url)->with('error','Invalid request');
        }

        $requestTime = $checkRequest->created_at->addHour();
        if( strtotime(Carbon::now()) > strtotime($requestTime) ){
            // $url = route('admin.login') . '?auth_token=zekkmdvhkm';
            $url = route('admin.login');
            return redirect($url)->with('error','Reset password link has been expired.');
        }
        return $this->__cbAdminView('auth.reset-password');
    }

    private function _resetPassword($request,$token)
    {
        $validator = Validator::make($request->all(), [
            'new_password'     => 'required|min:6|max:200',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return redirect()->withErrors($validator)->withInput();
        }
        $checkRequest = ResetPassword::getRequest($token);
        if( !isset($checkRequest->id) ){
            // $url = route('admin.login') . '?auth_token=zekkmdvhkm';
            $url = route('admin.login');
            return redirect($url)->with('error','Invalid request');
        }

        $requestTime = $checkRequest->created_at->addHour();
        if( strtotime(Carbon::now()) > strtotime($requestTime) ){
            // $url = route('admin.login') . '?auth_token=zekkmdvhkm';
            $url = route('admin.login');
            return redirect($url)->with('error','Reset password link has been expired.');
        }

        ResetPassword::updateResetPassword($request['new_password'],$token);
        // $url = route('admin.login') . '?auth_token=zekkmdvhkm';
        $url = route('admin.login');
        return redirect($url)->with('success','Password has been updated successfully');
    }


    public function createPassword(Request $request,$email,$login_url = null)
    {
        $login_url = Crypt::decrypt($login_url);

        if( $request->isMethod('post') ){
            return self::_createPassword($request,$email,$login_url);
        }
        $checkRequest = User::getClientVerfiy($email);
        if( !isset($checkRequest->id) ){
            // $url = route('admin.login') . '?auth_token=zekkmdvhkm';
            $url = $login_url ?? route('client.login');
            return redirect($url)->with('error','Invalid request');
        }

        return $this->__cbAdminView('auth.update-password');
    }

    private function _createPassword($request,$email,$login_url = null)
    {
        $validator = Validator::make($request->all(), [
            'new_password'     => 'required|min:6|max:200',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error','New password and confirm password must be same');
            // return redirect()->withErrors($validator)->withInput();
        }
        $checkRequest = User::getClientVerfiy($email);
        if( !isset($checkRequest->id) ){
            // $url = route('admin.login') . '?auth_token=zekkmdvhkm';
            // $url = route('admin.login');
            // return redirect($url)->with('error','Invalid request');
            return 'no client found';

        }
        $user = User::where('email', $checkRequest->email)->first();
        if ($user) {
            $user->password = bcrypt($request['new_password']);
            $user->save();
            
        }
        // $url = route('admin.login') . '?auth_token=zekkmdvhkm';
        // $url = route('client.login');
        // $url = 'https://estimates-kiwi-ticketing.vercel.app/';
        $url = $login_url ?? route('client.login');
        // dd('working');

        return redirect($url)->with('success','Password has been updated successfully');
    }
}
