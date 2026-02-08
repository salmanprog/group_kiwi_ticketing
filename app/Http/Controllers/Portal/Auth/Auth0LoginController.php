<?php

namespace App\Http\Controllers\Portal\Auth;

use App\Http\Controllers\Controller;
//use Auth0\Laravel\Facade\Auth0;
use Auth0\SDK\Auth0;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    // public function callback()
    // {
    //     // Initialize the SDK as you already have
    //     $auth0 = new Auth0([
    //         'domain'        => env('AUTH0_DOMAIN'),
    //         'clientId'      => env('AUTH0_CLIENT_ID'),
    //         'clientSecret'  => env('AUTH0_CLIENT_SECRET'),
    //         'redirectUri'   => env('AUTH0_REDIRECT_URI'),
    //         'cookieSecret'  => substr(base64_decode(str_replace('base64:', '', env('APP_KEY'))), 0, 32),
    //         'strategy'      => 'webapp',
    //     ]);

    //     try {
    //         $auth0->exchange();
    //         $auth0User = $auth0->getUser();
            
    //         // Optional: Get full credentials (tokens + user)
    //         // $credentials = $auth0->getCredentials();

    //         dd($auth0User);

    //         if (!$auth0User || empty($auth0User['email'])) {
    //             return redirect()->route('login')->with('error', 'Unable to fetch user email');
    //         }

    //         $user = User::where('email', $auth0User['email'])->where('auth0_id', $auth0User['sub'])->first();

    //         if (!$user) {
    //             // Create company
    //             $company = Company::create([
    //                 'name'       => $auth0User['name'] ?? $auth0User['email'],
    //                 'slug'       => Company::generateUniqueSlug(uniqid(uniqid())),
    //                 'email'      => $auth0User['email'],
    //                 'company_image_url' => $auth0User['picture'] ?? null,
    //                 'mobile_no'  => '1114555454',
    //                 'description' => 'test description',
    //                 'website' => 'www.google.com',
    //                 'address'   => 'test 123'
    //             ]);

    //             // Create company admin
    //             $username = CompanyAdmin::generateUniqueUserName($auth0User['name']);
    //             $companyAdmin = new CompanyAdmin();
    //             $companyAdmin->user_group_id = 2;
    //             $companyAdmin->auth0_id = $auth0User['sub'];
    //             $companyAdmin->user_type = 'company';
    //             $companyAdmin->slug = $username;
    //             $companyAdmin->username = $username;
    //             $companyAdmin->name = $auth0User['name'];
    //             $companyAdmin->email = $auth0User['email'];
    //             $companyAdmin->mobile_no = '1214558785';
    //             $companyAdmin->password = Hash::make('Test@123');
    //             $companyAdmin->status = 1;
    //             $companyAdmin->save();

    //             // Link company and admin
    //             CompanyUser::create([
    //                 'company_id' => $company->id,
    //                 'user_id' => $companyAdmin->id,
    //                 'user_type' => 'Admin',
    //                 'created_by' => '2',
    //                 'created_at' => Carbon::now()
    //             ]);

    //             // Assign to $user for login
    //             $user = $companyAdmin;
    //         }


    //         Auth::login($user);
    //         $getCompany = CompanyUser::getCompany($user->id);
    //         switch ($user->user_type) {
    //             case 'admin':
    //                 return redirect()->route('admin.dashboard');
    //             case 'manager':
    //                 return redirect()->route('manager.dashboard');
    //             case 'company':
    //                 if (isset($getCompany->status) && $getCompany->status == "0") {
    //                     Auth::logout();
    //                     return redirect()->back()->with('error', 'Company account has been disabled.');
    //                 }
    //                 return redirect()->route('company.dashboard');
    //             case 'salesman':
    //                 return redirect()->route('salesman.dashboard');
    //             case 'client':
    //             default:
    //                 return redirect()->route('client.dashboard');
    //         }
    //         //dd($user); 
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 400);
    //     }
    // }
    
    public function callback(Request $request)
    {
        try {
            // 1️⃣ Initialize Auth0 SDK
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
                'strategy'      => 'webapp', // handles PKCE automatically
            ]);

            // 2️⃣ Exchange authorization code for tokens
            $auth0->exchange();

            // 3️⃣ Get user profile and tokens
            $auth0User = $auth0->getUser();
            $credentials = $auth0->getCredentials();

            $idToken = $credentials->idToken;        // for external API
            $accessToken = $credentials->accessToken; // if needed for other API calls

             
            if (!$auth0User || empty($auth0User['email'])) {
                redirect($auth0->login());
            }

            session()->put([
                'auth0_id_token'     => $idToken,
                'auth0_access_token' => $accessToken,
            ]);
            $apiUrl = env('THIRD_PARTY_API_BASE_URL').'/api/Auth0Management/UserLogin';

            // Build query parameters
            $queryParams = [
                'userTokenId' => $idToken,
                'domain' => env('THIRD_PARTY_DOMAIN_URL'),
            ];

            // Send POST request with query string
            $externalApiResponse = Http::timeout(60)
                ->withHeaders([
                    'Accept' => '*/*',
                ])
                ->post($apiUrl . '?' . http_build_query($queryParams));
                
            if (!$externalApiResponse->successful()) {
                logger()->error('External API call failed', [
                    'status' => $externalApiResponse->status(),
                    'body' => $externalApiResponse->body(),
                ]);

                redirect($auth0->login());
            }

            $externalData = $externalApiResponse->json();

            // Only proceed when API returns success (errorCode 0)
            if (($externalData['errorCode'] ?? null) !== 0) {
                logger()->warning('Auth0 UserLogin API error', [
                    'errorCode' => $externalData['errorCode'] ?? null,
                    'errorMessage' => $externalData['errorMessage'] ?? 'Unknown',
                ]);
                return redirect()->route('admin.login')
                    ->with('error', $externalData['errorMessage'] ?? 'Login failed. Please try again.');
            }

            $userDetails = $externalData['data']['userDetails'] ?? [];
            $companyDetails = $externalData['data']['companyDetails'] ?? [];
            $email = $userDetails['email'] ?? $auth0User['email'];
            $auth0UserId = $userDetails['auth0UserId'] ?? $auth0User['sub'];
            $companyName = $companyDetails['companyName'] ?? null;
            $authCode = $companyDetails['authCode'] ?? null;

            // Find or create local user (using API data: email, company_name, auth_code)
            $user = User::where('email', $email)
                ->where('auth_code', $authCode)
                ->first();
           
            if (!$user) {
                 
                $name = trim(($userDetails['firstName'] ?? '') . ' ' . ($userDetails['lastName'] ?? '')) ?: ($auth0User['name'] ?? $email);
                
                $username = CompanyAdmin::generateUniqueUserName($name);
                $company = Company::create([
                    'name' => $companyName ?? $name,
                    'slug' => Company::generateUniqueSlug(uniqid()),
                    'email' => $email,
                    'image_url' => $companyDetails['logo'] ?? $userDetails['image'] ?? $auth0User['picture'] ?? null,
                    'mobile_no' => '1' . substr(md5($email), 0, 10),
                    'description' => 'Created via Auth0',
                    'website' => 'https://www.google.com',
                    'address' => '—',
                ]);

                $companyAdmin = CompanyAdmin::create([
                    'user_group_id' => 2,
                    'auth0_id' => $auth0UserId,
                    'user_type' => 'company',
                    'users_username' => $username,
                    'slug' => $username,
                    'username' => $username,
                    'name' => $name,
                    'email' => $email,
                    'auth_code' => $authCode,
                    'mobile_no' => '1' . substr(md5($email), 0, 10),
                    'password' => Hash::make('Auth0@Login'),
                    'image_url' => $userDetails['image'] ?? $auth0User['picture'] ?? null,
                    'status' => (int) ($userDetails['status'] ?? 1),
                ]);

                CompanyUser::create([
                    'company_id' => $company->id,
                    'user_id' => $companyAdmin->id,
                    'user_type' => 'Admin',
                    'created_by' => 2,
                    'created_at' => Carbon::now(),
                ]);

                $user = $companyAdmin;
            } else {
                // Update existing user with company_name and auth_code from API
                $user->auth_code = $authCode;
                $user->save();
            }

            Auth::login($user, true);

            $company = CompanyUser::getCompany($user->id);
            switch ($user->user_type) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'manager':
                    return redirect()->route('manager.dashboard');
                case 'company':
                    return redirect()->route('company.dashboard');
                case 'salesman':
                    return redirect()->route('salesman.dashboard');
                default:
                    return redirect()->route('client.dashboard');
            }
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            die();
            logger()->error('Auth0 Callback Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('login')
                ->with('error', 'Authentication failed. Please try again.');
        }
    }
    public function logout()
    {
        // Logout locally
        auth()->logout();

        // Create Auth0 instance
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
         
        // Redirect to Auth0 logout
        return redirect(
        $auth0->logout('http://127.0.0.1:8000/portal/login')
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
