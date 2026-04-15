<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Auth0\SDK\Auth0;

class ThirdPartyApiMiddleware
{
    protected $routeMap = [
        'company/dashboard'   => 'company.dashboard',
        'event-calander'      => 'event-calander',
        'organization'        => 'organization.index',
        'client-management'   => 'client-management.index',
        'manager-management'  => 'manager-management.index',
        'salesman-management' => 'salesman-management.index',
        'estimate'            => 'estimate.index',
        'contract'            => 'contract.index',
        'invoice'             => 'invoice.index',
        'product'             => 'product.index',
        'organization-type'   => 'organization-type.index',
        'event-type'          => 'event-type.index',
        'user-profile'        => 'admin.profile',
        'change-password'     => 'admin.change-password',
        'update-stripe-key'   => 'portal.update-stripe-key',
        'update-installment-status'   => 'portal.update-installment-status',
        'update-invoice-status' => 'portal.update-invoice-status',
        'company-profile'    => 'company-profile',
        'contract-emails'    => 'contract-emails',
        'hold-tickets'       => 'hold-tickets',
        'smtp-config'        => 'smtp-config.view',
        'email-template'     => 'email-template.index',
        'terms-and-conditions' =>'portal.terms-and-conditions',
    ];

    public function handle($request, Closure $next)
    {

        if (!Auth::check()) {
            return $next($request);
        }

        try {
            $idToken = session('auth0_id_token');
            $apiData = null;

            if ($idToken) {
                $apiUrl = env('THIRD_PARTY_API_BASE_URL') . '/api/Auth0Management/UserLogin';


                $response = Http::timeout(60)
                    ->withHeaders(['Accept' => 'application/json'])
                    ->post($apiUrl . '?' . http_build_query([
                        'userTokenId' => $idToken,
                        'domain'      => env('THIRD_PARTY_DOMAIN_URL'),
                    ]));
                // dd($response->json());
                dd($response->body());
                // dd($platform['platformPages']);
                if ($response->successful()) {
                    $data = json_decode($response->body(), true);
                    if (($data['errorCode'] ?? null) === 0) {
                        $apiData = $data['data'];
                        Session::put('thirdPartyApiData', $apiData);
                        session()->put([
                            'companyPlatformAccess'     => $apiData['companyPlatformAccess'] ?? null,
                        ]);
                        view()->share('thirdPartyApiData', $apiData);

                        if (isset($apiData['companyPlatformAccess']['platformPages'])) {
                            session()->put('platformPages', $apiData['companyPlatformAccess']['platformPages']);
                        } else {
                            $platform = collect($apiData['companyPlatformAccess'])
                                ->firstWhere('platformId', config('services.third_party.platform_id'));
                            if ($platform) {
                                session()->put('platformPages', $platform['platformPages'] ?? []);
                            }
                        }

                    }
                }
            }

            // ------------------- Permission Check -------------------
            // dd($apiData);

            if ($apiData) {
                $platformId = config('services.third_party.platform_id');
                $platform = collect($apiData['companyPlatformAccess'] ?? [])
                    ->firstWhere('platformId', $platformId);
                $permissions = [];
                if ($platform && isset($platform['permissions']) && is_array($platform['permissions'])) {
                    $permissions = collect($platform['permissions'])
                        ->pluck('permissionName')
                        ->map(fn($name) => strtolower(trim($name)))
                        ->toArray();
                }
                session()->put('userPermissions', $permissions);

                // Convert slugs to route names
                $allowedSlugs = [];
                if (!empty($platform['platformPages'])) {
                    foreach ($platform['platformPages'] as $category) {
                        $allowedSlugs = array_merge(
                            $allowedSlugs,
                            $this->getAllowedSlugs($category['subCategories'] ?? [])
                        );
                        if (!empty($category['pages'])) {
                            foreach ($category['pages'] as $page) {
                                if (($page['pageStatus'] ?? '') === 'publish') {
                                    $allowedSlugs[] = $page['pageSlug'];
                                }
                            }
                        }
                    }
                }



                $currentRouteName = $request->route() ? $request->route()->getName() : null;
                $currentPath = $request->path(); // e.g., portal/organization/ajax-listing
                // Skip checking no-permission route to avoid loop
                if ($currentRouteName && $currentRouteName !== 'no-permission') {
                    // Check if current route matches exactly OR starts with a base route
                    $isAllowed = false;

                    
                    foreach ($this->routeMap as $slug => $route) {
                        if (in_array($route, $allowedRoutes)) {
                            // Allow exact match
                            if ($currentRouteName === $route) {
                                $isAllowed = true;
                                break;
                            }
                            // Allow URLs like /organization/ajax-listing
                            if (str_contains($currentPath, $slug)) {
                                $isAllowed = true;
                                break;
                            }
                        }
                    }

                    if (!$isAllowed) {
                        return redirect()->route('no-permission');
                    }
                }
            }

        } catch (\Throwable $e) {
            logger()->error('ThirdPartyApiMiddleware Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }

        return $next($request);
    }

    protected function getAllowedSlugs($categories)
    {
        $slugs = [];

        foreach ($categories as $category) {
            if (!empty($category['pages'])) {
                foreach ($category['pages'] as $page) {
                    $slugs[] = $page['pageSlug'];
                }
            }

            if (!empty($category['subCategories'])) {
                $slugs = array_merge($slugs, $this->getAllowedSlugs($category['subCategories']));
            }
        }

        return $slugs;
    }
}
