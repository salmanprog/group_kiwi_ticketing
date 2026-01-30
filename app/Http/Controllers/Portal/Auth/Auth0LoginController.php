<?php

namespace App\Http\Controllers\Portal\Auth;

use App\Http\Controllers\Controller;
//use Auth0\Laravel\Facade\Auth0;
use Auth0\SDK\Auth0;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,CompanyAdmin,CompanyUser,Company};

class Auth0LoginController extends Controller
{
    public function login()
    {
    //     $state = Str::random(40);
    // session(['auth0_state' => $state]);

    // $query = http_build_query([
    //     'client_id' => env('AUTH0_CLIENT_ID'),
    //     'redirect_uri' => env('AUTH0_REDIRECT_URI'),
    //     'response_type' => 'code',
    //     'scope' => env('AUTH0_SCOPE', 'openid profile email'),
    //     'state' => $state,
    // ]);

    // return redirect('https://' . env('AUTH0_DOMAIN') . '/authorize?' . $query);

        $auth0 = new Auth0([
            'domain'        => env('AUTH0_DOMAIN'),
            'clientId'      => env('AUTH0_CLIENT_ID'),
            'clientSecret'  => env('AUTH0_CLIENT_SECRET'),
            'redirectUri'   => env('AUTH0_REDIRECT_URI'),
            'cookieSecret'  => substr(
                base64_decode(str_replace('base64:', '', env('APP_KEY'))),
                0,
                32
            ),
            'strategy'      => 'webapp',
        ]);

        return redirect($auth0->login());
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
            $auth0User = $auth0->getUser();
            // Optional: Get full credentials (tokens + user)
            // $credentials = $auth0->getCredentials();
            if (!$auth0User || empty($auth0User['email'])) {
                return redirect()->route('login')->with('error', 'Unable to fetch user email');
            }

            $user = User::where('email', $auth0User['email'])->first();

            if (!$user) {
                // Create company
                $company = Company::create([
                    'name'       => $auth0User['name'] ?? $auth0User['email'],
                    'slug'       => Company::generateUniqueSlug(uniqid(uniqid())),
                    'email'      => $auth0User['email'],
                    'company_image_url' => $auth0User['picture'] ?? null,
                    'mobile_no'  => '1231231234',
                    'description' => 'test description',
                    'website' => 'www.google.com',
                    'address'   => 'test 123'
                ]);

                // Create company admin
                $username = CompanyAdmin::generateUniqueUserName($auth0User['name']);
                $companyAdmin = new CompanyAdmin();
                $companyAdmin->user_group_id = 2;
                $companyAdmin->user_type = 'company';
                $companyAdmin->slug = $username;
                $companyAdmin->username = $username;
                $companyAdmin->name = $auth0User['name'];
                $companyAdmin->email = $auth0User['email'];
                $companyAdmin->mobile_no = '1231231234';
                $companyAdmin->password = Hash::make('Test@123');
                $companyAdmin->status = 1;
                $companyAdmin->save();

                // Link company and admin
                CompanyUser::create([
                    'company_id' => $company->id,
                    'user_id' => $companyAdmin->id,
                    'user_type' => 'Admin',
                    'created_by' => '2',
                    'created_at' => Carbon::now()
                ]);

                // Assign to $user for login
                $user = $companyAdmin;
            }


            Auth::login($user);
            $getCompany = CompanyUser::getCompany($user->id);
            switch ($user->user_type) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'manager':
                    return redirect()->route('manager.dashboard');
                case 'company':
                    if (isset($getCompany->status) && $getCompany->status == "0") {
                        Auth::logout();
                        return redirect()->back()->with('error', 'Company account has been disabled.');
                    }
                    return redirect()->route('company.dashboard');
                case 'salesman':
                    return redirect()->route('salesman.dashboard');
                case 'client':
                default:
                    return redirect()->route('client.dashboard');
            }
            //dd($user); 
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

    protected function redirectByRole($user)
    {
        $getCompany = CompanyUser::getCompany($user->id);

        // if ($user->status == "0") {
        //     Auth::logout();
        //     return redirect()->back()->with('error', 'Your account has been disabled');
        // }

        if ($user->user_type === "admin") {
            return redirect()->route('admin.dashboard');
        }

        // if (isset($getCompany->status) && $getCompany->status == "0") {
        //     Auth::logout();
        //     return redirect()->back()->with('error', 'Company account has been disabled');
        // }

        return match ($user->user_type) {
            'manager'  => redirect()->route('manager.dashboard'),
            'company'  => redirect()->route('company.dashboard'),
            'salesman' => redirect()->route('salesman.dashboard'),
            'client'   => redirect()->route('client.dashboard'),
            default    => redirect()->route('dashboard'),
        };
    }
}
