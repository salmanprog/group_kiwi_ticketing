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
use Illuminate\Support\Facades\DB;
use App\Models\{User,UserGroup,CompanyAdmin,CompanyUser,Company};

class Auth0LoginController extends Controller
{
    public function login()
    {
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

    public function callback(Request $request)
    {
        try {
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

            $auth0->exchange();
            
            $auth0User = $auth0->getUser();
            $credentials = $auth0->getCredentials();

            $idToken = $credentials->idToken;        // for external API
            $accessToken = $credentials->accessToken; // if needed for other API calls

             
            if (!$auth0User || empty($auth0User['email'])) {
                redirect($auth0->login());
            }

             
            $apiUrl = 'https://dynamicpricing-api.dynamicpricingbuilder.com/api/Auth0Management/UserLogin';

            // Build query parameters
            $queryParams = [
                'userTokenId' => $idToken,
                'domain' => env('AUTH0_DOMAIN'),
            ];

            // Send POST request with query string
            $externalApiResponse = Http::timeout(60)
                ->withHeaders([
                    'Accept' => '*/*',
                ])
                ->post($apiUrl . '?' . http_build_query($queryParams));
                
            dd([
                'status' => $externalApiResponse->status(),
                'ok'     => $externalApiResponse->ok(),
                'body'   => $externalApiResponse->body(),
            ]);
            die();
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
            $userRoles = $externalData['data']['userRoles'] ?? [];
            $permissionDetails = $externalData['data']['permissionDetails'] ?? [];
            $email = $userDetails['email'] ?? $auth0User['email'];
            $auth0UserId = $userDetails['auth0UserId'] ?? $auth0User['sub'];
            $companyName = $companyDetails['companyName'] ?? null;
            $authCode = $companyDetails['authCode'] ?? null;
            $domain = $companyDetails['companyDomain'] ?? null;
             print_r($permissionDetails);
            die();
            
            $userGroup = UserGroup::where('title', $userRoles[0]['roleName'])->first();
            $user = User::where('email', $email)
                ->where('auth_code', $authCode)
                ->where('user_group_id', $userGroup->id)
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
                    'user_group_id' => $userGroup->id,
                    'auth0_id' => $auth0UserId,
                    'user_type' => 'company',
                    'users_username' => $username,
                    'slug' => $username,
                    'username' => $username,
                    'name' => $name,
                    'email' => $email,
                    'auth_code' => $authCode,
                    'companyDomain' => $domain,
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
            
            $permissions = $permissionDetails;
            $cmsRoleId = $userGroup->id;

            foreach ($permissions as $permission) {
                $permissionName = $permission['permissionName'];

                // Default flags
                $flags = [
                    'is_add'    => '0',
                    'is_view'   => '0',
                    'is_update' => '0',
                    'is_delete' => '0',
                ];

                // Extract module name dynamically from permission
                if (str_contains($permissionName, ':')) {
                    [$action, $moduleName] = explode(':', $permissionName);

                    // Fetch module from DB dynamically
                    $module = DB::table('cms_modules')
                        ->where('name', $moduleName)
                        ->first();

                    if (!$module) {
                        // Module not found, skip to next permission
                        continue;
                    }

                    // Set flags based on action
                    match ($action) {
                        'Create' => $flags['is_add'] = '1',
                        'Read'   => $flags['is_view'] = '1',
                        'Update' => $flags['is_update'] = '1',
                        'Delete' => $flags['is_delete'] = '1',
                        default  => null,
                    };
                } else {
                    // Handle global "View" permission (if needed)
                    // if ($permissionName === 'View') {
                    //     $moduleName = 'User'; // or whatever default module
                    //     $module = DB::table('cms_modules')
                    //         ->where('name', $moduleName)
                    //         ->first();

                    //     if (!$module) continue;

                        $flags['is_view'] = '1';
                    // }
                }

                // Insert or update cms_module_permissions
                DB::table('cms_module_permissions')->updateOrInsert(
                    [
                        'user_id'   => $user->id,
                        'user_group_id'   => $cmsRoleId,
                        'cms_module_id' => $module->id,
                    ],
                    [
                        'is_add'     => $flags['is_add'],
                        'is_view'    => $flags['is_view'],
                        'is_update'  => $flags['is_update'],
                        'is_delete'  => $flags['is_delete'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
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
    // public function logout()
    // {
        // Logout locally
        //auth()->logout();

        // Create Auth0 instance
        // $auth0 = new Auth0([
        //     'domain'        => env('AUTH0_DOMAIN'),
        //     'clientId'      => env('AUTH0_CLIENT_ID'),
        //     'clientSecret'  => env('AUTH0_CLIENT_SECRET'),
        //     'redirectUri'   => env('AUTH0_REDIRECT_URI'),
        //     'cookieSecret'  => substr(
        //         base64_decode(str_replace('base64:', '', env('APP_KEY'))),
        //         0,
        //         32
        //     ),
        //     'strategy'      => 'webapp',
        // ]);
         
        // Redirect to Auth0 logout
    //}

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        $returnTo = env('APP_URL') . '/portal/login';

        $logoutUrl = 'https://' . env('AUTH0_DOMAIN') . '/v2/logout?' . http_build_query([
            'client_id' => env('AUTH0_CLIENT_ID'),
            'returnTo'  => $returnTo,
        ]);

        return redirect($logoutUrl);
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
