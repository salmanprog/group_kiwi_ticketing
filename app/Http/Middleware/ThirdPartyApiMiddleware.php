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
        'update-invoice-status' => 'portal.update-invoice-status'
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

                if ($response->successful()) {
                    $data = json_decode($response->body(), true);
                    if (($data['errorCode'] ?? null) === 0) {
                        $apiData = $data['data'];
                        Session::put('thirdPartyApiData', $apiData);
                        session()->put([
                            'companyPlatformAccess'     => $apiData['companyPlatformAccess'] ?? null,
                        ]);
                        view()->share('thirdPartyApiData', $apiData);
                    }
                }
            }

            // ------------------- Permission Check -------------------

            if ($apiData && isset($apiData['platform'][0]['categories'])) {
                $allowedSlugs = $this->getAllowedSlugs($apiData['platform'][0]['categories']);

                // Convert slugs to route names
                $allowedRoutes = [];
                foreach ($allowedSlugs as $slug) {
                    if (isset($this->routeMap[$slug])) {
                        $allowedRoutes[] = $this->routeMap[$slug];
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
