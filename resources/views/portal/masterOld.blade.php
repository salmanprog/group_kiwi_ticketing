<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ !empty(appSetting('application_setting', 'application_name'))
            ? appSetting('application_setting', 'application_name')
            : env('APP_NAME') }}
        | Admin Panel
    </title>
    <link rel="icon" type="image/png" sizes="16x16"
        href="https://kiwiticketing.com/wp-content/uploads/2024/09/Group-918.png">
    <link href="{{ asset('admin/assets/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/lib/toast/jquery.toast.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/scss/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('stylesheets')
    <script>
        var base_url = '{{ URL::to('/') }}';
        var current_url = '{{ url()->current() }}';
    </script>
    <style>
        .nav-link.active {
            color: #9FC241 !important;
            font-weight: 600;
        }

        /* .nav-item.active>.nav-link {
            color: #9FC241;
        } */

        /* Skeleton Loading Styles */
        .skeleton-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 9999;
            overflow: hidden;
            transition: opacity 0.3s ease;
        }

        .skeleton-loading.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .skeleton-header {
            height: 70px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .skeleton-logo {
            width: 205px;
            height: 40px;
            background: #e9ecef;
            border-radius: 4px;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
        }

        .skeleton-sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            width: 250px;
            height: calc(100% - 70px);
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding: 20px 0;
        }

        .skeleton-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .skeleton-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e9ecef;
            margin-bottom: 10px;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
        }

        .skeleton-name {
            width: 120px;
            height: 16px;
            background: #e9ecef;
            border-radius: 4px;
            margin-bottom: 8px;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
        }

        .skeleton-welcome {
            width: 80px;
            height: 14px;
            background: #e9ecef;
            border-radius: 4px;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
        }

        .skeleton-menu-item {
            height: 40px;
            margin: 8px 15px;
            background: #e9ecef;
            border-radius: 4px;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
        }

        .skeleton-content {
            margin-left: 250px;
            padding: 100px 20px 20px;
        }

        .skeleton-page-title {
            width: 200px;
            height: 32px;
            background: #e9ecef;
            border-radius: 4px;
            margin-bottom: 20px;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
        }

        .skeleton-card {
            height: 200px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
        }

        @keyframes skeleton-pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        /* Hide actual content during loading */
        .content-hidden {
            opacity: 0;
        }

        .content-visible {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .main-sidebar-nav {
            height: 100vh;
        }

        .sidebar-logo-bottom {
            flex-shrink: 0;
            position: sticky;
            bottom: 0;
            background: white;
            z-index: 10;
        }


        /* new gr navs */

        .static-sidebar-menu {
            padding-inline: 16px;
            font-family: "Poppins", sans-serif !important;
        }

        .static-sidebar-menu .submenu {
            display: none;
            padding-left: 30px;
            list-style: none;
            margin: 0;
        }

        .static-sidebar-menu .nested-submenu {
            display: none;
            padding-left: 20px;
            list-style: none;
            margin: 0;
        }

        .static-sidebar-menu .arrow-icon {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .static-sidebar-menu .submenu-link {
            padding: 8px 15px;
            display: block;
            color: #333;
            text-decoration: none;
        }

        .static-sidebar-menu .submenu-link:hover,
        .static-sidebar-menu .submenu-link.active {
            background-color: #f0f0f0;
            color: #007bff;
        }

        .static-sidebar-menu .nav-link {
            padding: 16px 15px;
            display: flex;
            align-items: center;
            color: #4b5563;
            text-decoration: none;
            border-radius: 13px;
            margin-bottom: 2px;
            font-weight: 600;
            font-size: 14px;
            line-height: 14px;
        }

        .static-sidebar-menu .nav-link:hover {
            background-color: #f5f5f5;
            color: #111827;
        }

        .static-sidebar-menu .nav-link:hover i {
            color: #9fc23f;
        }

        .static-sidebar-menu .nav-link.active {
            background-color: #e9ecef;
            color: #007bff;
            font-weight: 600;
        }

        .static-sidebar-menu .nav-link i:first-child {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .static-sidebar-menu .nav-link i {
            color: #9ca3af;
        }

        .nav>li>a:hover {
            color: #4b5563;
        }

        /* .static-sidebar-menu .submenu-link:hover,
        .static-sidebar-menu .submenu-link.active {
            color: #4b5563;
        } */

        .menu-toggle {
            background: #fff !important;
        }

        .nav-collapsed .static-sidebar-menu {
            width: 84px;
        }

        .nav-collapsed .static-sidebar-menu .fa-chevron-down {
            display: none;
        }

        .nav-collapsed .static-sidebar-menu .submenu {
            position: absolute;
            background: #fff;
            left: 83px;
        }

        li.nav-item.active a {
            background: #9FC23F;
            color: #fff;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        li.nav-item.active a i {
            color: #fff;
        }

        li.nav-item.active a:hover {
            background: #9FC23F;
            color: #fff;
        }

        li.nav-item.active a:hover i {
            color: #fff;
        }

        .nav-collapsed .static-sidebar-menu .nav-link i:first-child {
            margin-right: 0;
        }

        .nav-collapsed .sidebar-logo-bottom {
            display: none;
        }

        /* a.nav-link.menu-toggle.has-dtex-tr[data-expanded="true"] {
            background: #9FC23F !important;
            color: #fff !important;
        }

        a.nav-link.menu-toggle.has-dtex-tr[data-expanded="true"] i {
            color: #fff !important;
        }

        ul.nested-submenu li.nav-item.active a {
            color: #9FC23F;
            background: #ffffff;
        } */

        /* platforms menue css */

        .menu-platform {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;

        }

        .menu-platform:hover {
            background-color: #9fc23f0d;
        }

        .icon-box {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: rgba(59, 130, 246, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            transition: transform 0.3s ease;
        }

        .group:hover .icon-box {
            transform: scale(1.1);
        }

        .icon-box img {
            width: 16px;
            height: 16px;
            opacity: 0.8;
        }

        .platform-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .platform-title {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.3s ease;
            line-height: 16px;
        }

        .group:hover .platform-title {
            color: #3b82f6;
        }

        .platform-subtitle {
            font-size: 9px;
            font-weight: 500;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 14px;
        }

        .platform-subtitle svg {
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <!-- Skeleton Loading Screen -->
    <div class="skeleton-loading" id="skeletonLoader">
        <div class="skeleton-header">
            <div class="skeleton-logo"></div>
        </div>
        <div class="skeleton-sidebar">
            {{-- <div class="skeleton-profile">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-name"></div>
                <div class="skeleton-welcome"></div>
            </div> --}}
            <div class="skeleton-menu-item"></div>
            <div class="skeleton-menu-item"></div>
            <div class="skeleton-menu-item"></div>
            <div class="skeleton-menu-item"></div>
            <div class="skeleton-menu-item"></div>
        </div>
        <div class="skeleton-content">
            <div class="skeleton-page-title"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
        </div>
    </div>

    <!-- Original Content (Initially Hidden) -->
    <div id="mainContent" class="content-hidden">
        <div id="overlay"></div>
        <div class="top-bar primary-top-bar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col cust-header-css">
                        @if (Auth::user()->user_type == 'admin')
                            <a class="admin-logo a" href="{{ route('admin.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                </h1>
                            </a>
                        @elseif (Auth::user()->user_type == 'company')
                            @php
                                // Fetch company record for logged-in user
                                $company = \App\Models\Company::where(
                                    'id',
                                    \App\Models\CompanyUser::getCompany(Auth::id())->id,
                                )->first();
                                $company_logo = $company ? $company->image_url : null;
                            @endphp
                            <a class="admin-logo c" href="{{ route('company.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                    <!-- Large logo -->
                                    <!-- <img style="width: 205px;" alt="logo"
                                        src="{{ $company_logo ? \Storage::url($company_logo) : 'https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png' }}"
                                        class="toggle-none hidden-xs"> -->

                                    <!-- Small/collapsed logo -->
                                    <!-- <img style="width: 45px; display:none; padding-top: 10px;" alt="logo"
                                        src="{{ $company_logo ? \Storage::url($company_logo) : 'https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png' }}"
                                        class="for-coll"> -->
                                </h1>
                            </a>
                        @elseif (Auth::user()->user_type == 'manager')
                            <a class="admin-logo m" href="{{ route('manager.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                </h1>
                            </a>
                        @elseif (Auth::user()->user_type == 'salesman')
                            <a class="admin-logo s" href="{{ route('salesman.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                </h1>
                            </a>
                        @else
                            <a class="admin-logo n" href="{{ route('client.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                </h1>
                            </a>
                        @endif
                        <div class="left-nav-toggle">
                            <a href="#" class="nav-collapse">
                                <button class="custom-icon-side-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-chevron-left ">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                </button>
                            </a>
                        </div>
                        <div class="left-nav-collapsed">
                            <a href="#" class="nav-collapsed">

                                <button class="custom-icon-side-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-chevron-left ">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                </button>

                            </a>
                        </div>
                        <ul class="list-inline top-right-nav">
                            <li class="dropdown avtar-dropdown">
                                <a class="dropdown-toggle  cust-dropdown-toggle" data-toggle="dropdown"
                                    href="#">
                                    <img alt="" class="rounded-circle"
                                        src="{{ URL::to('admin/assets/img/avtar-2.png') }}"
                                        width="30">
                                    {{ currentUser()->name }}
                                </a>
                                <ul class="dropdown-menu top-dropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.profile') }}"><i
                                                class="icon-user"></i> Profile</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.change-password') }}"><i
                                                class="icon-lock"></i> Change Password</a>
                                    </li>
                                    @if (Auth::user()->user_type == 'company')
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('portal.update-stripe-key') }}"><i
                                                    class="icon-key"></i> Update Stripe Key</a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->user_type == 'company')
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('portal.terms-and-conditions') }}"><i
                                                    class="fa-solid fa-file-signature"></i> Terms & Conditions</a>
                                        </li>
                                    @endif
                                    <li class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.logout') }}"><i
                                                class="icon-logout"></i> Logout</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="main-sidebar-nav default-navigation all-fonts-col">
            <div class="nano">
                <div class="nano-content sidebar-nav" style="display: flex; flex-direction: column; height: 100%;">
                    <div style="flex-grow: 1; overflow-y: auto;">

                        <!-- Static Sidebar Navigation Menu - Working Version -->
                        <div class="static-sidebar-menu" style="margin-top: 20px;">
                        @php
                            // ====================== Variables ======================
                            $userDetails   = $thirdPartyApiData['userDetails'] ?? [];
                            $companyDetails = $thirdPartyApiData['companyDetails'] ?? [];
                            $userRoles     = $thirdPartyApiData['userRoles'] ?? [];
                            $platforms     = $thirdPartyApiData['platform'] ?? [];
                            $permissions   = $thirdPartyApiData['permissionDetails'] ?? [];

                            // Dashboard routes by user type
                            $dashboards = [
                                'admin'    => 'admin.dashboard',
                                'company'  => 'company.dashboard',
                                'manager'  => 'manager.dashboard',
                                'salesman' => 'salesman.dashboard',
                                'client'   => 'client.dashboard',
                            ];

                            // PageSlug -> Route Map
                            $routeMap = [
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
                                'user-profile'        => 'admin.profile', // example
                                'change-password'     => 'admin.change-password',
                                'update-stripe-key'   => 'portal.update-stripe-key',
                            ];

                            // ====================== Recursive Submenu Renderer ======================
                            function renderSubMenu($category, $routeMap, $level = 1)
                            {
                                $ulClass = $level === 1 ? 'submenu' : 'nested-submenu';
                                echo '<ul class="' . $ulClass . '">';

                                // Pages
                                if (!empty($category['pages'])) {
                                    foreach ($category['pages'] as $page) {
                                        $routeName = $routeMap[$page['pageSlug']] ?? null;
                                        $href = $routeName ? route($routeName) : 'javascript:void(0)';

                                        echo '<li class="nav-item">';
                                        echo '<a class="nav-link ' . ($level === 1 ? 'submenu-link' : '') . '" href="' . $href . '">';
                                        echo e($page['pageName'] ?? $page['pageSlug']);
                                        echo '</a>';
                                        echo '</li>';
                                    }
                                }

                                // Nested subCategories
                                if (!empty($category['subCategories'])) {
                                    foreach ($category['subCategories'] as $sub) {
                                        echo '<li class="nav-item has-submenu">';
                                        echo '<a class="nav-link submenu-link menu-toggle" href="javascript:void(0);" data-expanded="false">';
                                        echo e($sub['categoryName']);
                                        echo '<i class="fas fa-chevron-down arrow-icon"></i>';
                                        echo '</a>';

                                        renderSubMenu($sub, $routeMap, $level + 1);

                                        echo '</li>';
                                    }
                                }

                                echo '</ul>';
                            }
                            @endphp

                            {{-- ====================== Sidebar Menu ====================== --}}
                            <ul class="nav flex-column" id="dynamicMenu">

                                {{-- Dashboard (static) --}}
                                <li data-type="parent"
                                    class="nav-item {{ request()->routeIs($dashboards[Auth::user()->user_type]) ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route($dashboards[Auth::user()->user_type]) }}">
                                        <i class="grid-icon"></i>
                                        <span class="toggle-none">Dashboard</span>
                                    </a>
                                </li>

                                {{-- API-driven categories (SideMenu only) --}}
                                @foreach($platforms[0]['categories'] as $category)

                                    @if($category['menuPosition'] !== 'SideMenu')
                                        @continue
                                    @endif

                                    @php
                                        $pages = $category['pages'] ?? [];
                                        $hasSubCategories = !empty($category['subCategories']);
                                        $hasMultiplePages = count($pages) > 1;
                                        $isDropdown = $hasSubCategories || $hasMultiplePages;

                                        // Skip Dashboard from API
                                        if (isset($pages[0]['pageSlug']) && $pages[0]['pageSlug'] === 'company/dashboard') {
                                            continue;
                                        }
                                    @endphp

                                    {{-- Dropdown --}}
                                    @if($isDropdown)
                                        <li data-type="child" class="nav-item">
                                            <a class="nav-link menu-toggle" href="javascript:void(0);" data-expanded="false">
                                                <i class="fas fa-folder"></i>
                                                <span class="toggle-none">{{ $category['categoryName'] }}</span>
                                                <i class="fas fa-chevron-down arrow-icon"></i>
                                            </a>

                                            <ul class="submenu">
                                                {{-- Pages --}}
                                                @foreach($pages as $page)
                                                    @php
                                                        $routeName = $routeMap[$page['pageSlug']] ?? null;
                                                    @endphp
                                                    <li class="nav-item">
                                                        <a class="nav-link submenu-link"
                                                        href="{{ $routeName ? route($routeName) : 'javascript:void(0)' }}">
                                                            {{ $page['pageName'] }}
                                                        </a>
                                                    </li>
                                                @endforeach

                                                {{-- SubCategories --}}
                                                @if($hasSubCategories)
                                                    @foreach($category['subCategories'] as $sub)
                                                        <li class="nav-item has-submenu">
                                                            <a class="nav-link submenu-link menu-toggle"
                                                            href="javascript:void(0);" data-expanded="false">
                                                                {{ $sub['categoryName'] }}
                                                                <i class="fas fa-chevron-down arrow-icon"></i>
                                                            </a>

                                                            {!! renderSubMenu($sub, $routeMap) !!}
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>

                                    {{-- Single link --}}
                                    @else
                                        @php
                                            $routeName = $routeMap[$pages[0]['pageSlug']] ?? null;
                                        @endphp
                                        <li data-type="parent" class="nav-item">
                                            <a class="nav-link" href="{{ $routeName ? route($routeName) : 'javascript:void(0)' }}">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span class="toggle-none">{{ $category['categoryName'] }}</span>
                                            </a>
                                        </li>
                                    @endif

                                @endforeach
                                {{-- ====================== Profile Dropdown ====================== --}}
                                @foreach($platforms[0]['categories'] as $category)
                                    @if($category['menuPosition'] !== 'ProfileMenu')
                                        @continue
                                    @endif

                                    @php
                                        $pages = $category['pages'] ?? [];
                                    @endphp

                                    <li class="nav-item has-submenu">
                                        <a class="nav-link menu-toggle" href="javascript:void(0);" data-expanded="false">
                                            <i class="fas fa-user"></i>
                                            <span class="toggle-none">Profile</span>
                                            <i class="fas fa-chevron-down arrow-icon"></i>
                                        </a>

                                        <ul class="submenu">
                                            @foreach($pages as $page)
                                                @php
                                                    $routeName = $routeMap[$page['pageSlug']] ?? null;
                                                @endphp
                                                <li class="nav-item">
                                                    <a class="nav-link submenu-link" href="{{ $routeName ? route($routeName) : 'javascript:void(0)' }}">
                                                        {{ $page['pageName'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="sidebar-logo-bottom"
                        style="padding: 15px; text-align: center; border-top: 1px solid #eee; margin-top: auto;">
                        <img src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                            alt="Company Logo" style="max-width: 232px; height: auto;">
                        {{-- <p style="margin-top: 8px; margin-bottom: 0; color: #666; font-size: 12px;">
                            © {{ date('Y') }} Company Name
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="main-header-wrapper-for-fixed">
            <div class="row page-header align-items-center justify-content-between cust-p">
                <div class="col-lg-6">
                    <h2>{{ $page_title ?? '' }}</h2>
                </div>
                <div class="col-lg-6">
                    @if (Auth::user()->user_type == 'company')
                    @if (empty(Auth::user()->test_publishable_key) && empty(Auth::user()->test_secret_key) && (empty(Auth::user()->live_publishable_key) && empty(Auth::user()->live_secret_key)))
                    <strong class="text-danger">Please update Stripe key</strong>
                    @endif
                    @endif
                </div>
            </div>
        </div> --}}

        <!-- Content Area -->
        <div id="content-area">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('admin/assets/lib/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/pace/pace.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/nano-scroll/jquery.nanoscroller.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/metisMenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/toast/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.mask.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="{{ asset('admin/assets/js/custom.js') }}"></script>
    <script src="{{ asset('admin/assets/js/admin.js') }}"></script>
    <script src="{{ asset('admin/assets/js/productEstimate.js') }}"></script>
    <script src="{{ asset('admin/assets/js/taxProductEstimate.js') }}"></script>
    <script src="{{ asset('admin/assets/js/productDiscount.js') }}"></script>
    <script src="{{ asset('admin/assets/js/productPayment.js') }}"></script>

    <script>
        // Page load complete hone par skeleton hide karo
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for all resources to load
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const skeletonLoader = document.getElementById('skeletonLoader');
                    const mainContent = document.getElementById('mainContent');

                    if (skeletonLoader && mainContent) {
                        // Skeleton ko hide karo
                        skeletonLoader.classList.add('hidden');

                        // Main content ko show karo
                        mainContent.classList.remove('content-hidden');
                        mainContent.classList.add('content-visible');

                        // Skeleton ko completely remove karo after transition
                        setTimeout(function() {
                            skeletonLoader.style.display = 'none';
                        }, 300);
                    }
                }, 1000); // Minimum 500ms ke liye loader dikhaye
            });

            // Fallback: Agar load event na fire ho to bhi skeleton hide ho jaye
            setTimeout(function() {
                const skeletonLoader = document.getElementById('skeletonLoader');
                const mainContent = document.getElementById('mainContent');

                if (skeletonLoader && mainContent && !skeletonLoader.classList.contains('hidden')) {
                    skeletonLoader.classList.add('hidden');
                    mainContent.classList.remove('content-hidden');
                    mainContent.classList.add('content-visible');

                    setTimeout(function() {
                        skeletonLoader.style.display = 'none';
                    }, 300);
                }
            }, 3000); // Maximum 3 seconds ke baad
        });

        // Browser back/forward navigation ke liye bhi
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Agar page cache se load ho raha hai
                const skeletonLoader = document.getElementById('skeletonLoader');
                const mainContent = document.getElementById('mainContent');

                if (skeletonLoader && mainContent) {
                    skeletonLoader.style.display = 'none';
                    mainContent.classList.remove('content-hidden');
                    mainContent.classList.add('content-visible');
                }
            }
        });

        // $(document).ready(function() {
        //     // Disable any other menu libraries
        //     if ($.fn.metisMenu) {
        //         $('#menu').metisMenu('dispose');
        //     }

        //     // Simple toggle for all menus
        //     $('.static-sidebar-menu').on('click', '.menu-toggle', function(e) {
        //         e.preventDefault();
        //         e.stopPropagation();

        //         var $this = $(this);
        //         var $submenu = $this.next('ul');
        //         var $arrow = $this.find('.arrow-icon');

        //         // Toggle current menu
        //         $submenu.slideToggle(200);

        //         // Rotate arrow
        //         if ($submenu.is(':visible')) {
        //             $this.attr('data-expanded', 'true');
        //             $arrow.css('transform', 'rotate(180deg)');
        //             $arrow.removeClass('fa-chevron-right').addClass('fa-chevron-down');
        //         } else {
        //             $this.attr('data-expanded', 'false');
        //             $arrow.css('transform', 'rotate(0deg)');
        //             $arrow.removeClass('fa-chevron-down').addClass('fa-chevron-right');
        //         }

        //         // Close other main menus (optional)
        //         if (!$this.closest('.has-submenu').length) {
        //             $('.static-sidebar-menu .menu-toggle').not($this).each(function() {
        //                 var $otherToggle = $(this);
        //                 var $otherSubmenu = $otherToggle.next('ul');
        //                 var $otherArrow = $otherToggle.find('.arrow-icon');

        //                 if ($otherSubmenu.is(':visible') && !$otherToggle.closest('.has-submenu')
        //                     .length) {
        //                     $otherSubmenu.slideUp(200);
        //                     $otherToggle.attr('data-expanded', 'false');
        //                     $otherArrow.css('transform', 'rotate(0deg)');
        //                     $otherArrow.removeClass('fa-chevron-down').addClass('fa-chevron-right');
        //                 }
        //             });
        //         }
        //     });

        //     // Initially show already expanded menus
        //     $('.static-sidebar-menu .menu-toggle[data-expanded="true"]').each(function() {
        //         $(this).next('ul').show();
        //     });
        // });


        $(document).ready(function() {
            // Disable any other menu libraries
            if ($.fn.metisMenu) {
                $('#menu').metisMenu('dispose');
            }

            // Function to expand parent menu of active item - MUST RUN FIRST
            function expandParentOfActiveItem() {
                console.log('Running expandParentOfActiveItem...');

                // Find all active nav items
                $('.static-sidebar-menu .nav-item.active').each(function() {
                    var $activeItem = $(this);
                    console.log('Active item found:', $activeItem);

                    // Find ALL parent submenus
                    $activeItem.parents('.submenu, .nested-submenu').each(function(index) {
                        var $submenu = $(this);
                        var $menuToggle = $submenu.prev('.menu-toggle');

                        if ($menuToggle.length) {
                            console.log('Expanding parent menu #' + (index + 1) + ':', $menuToggle
                                .find('span').text());

                            // Directly set everything - NO animations
                            $submenu.show();
                            $menuToggle.attr('data-expanded', 'true');
                            $menuToggle.find('.arrow-icon').css('transform', 'rotate(180deg)');
                        }
                    });
                });
            }

            // === RUN THIS IMMEDIATELY ===
            expandParentOfActiveItem();

            // Simple toggle for all menus
            $('.static-sidebar-menu').on('click', '.menu-toggle', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var $this = $(this);
                var $submenu = $this.next('ul');
                var $arrow = $this.find('.arrow-icon');

                // Check current expanded state from data attribute
                var isExpanded = $this.attr('data-expanded') === 'true';

                console.log('Menu toggle clicked. Current expanded:', isExpanded);

                // Toggle based on current state
                if (isExpanded) {
                    // Close it
                    $submenu.slideUp(200);
                    $this.attr('data-expanded', 'false');
                    $arrow.css('transform', 'rotate(0deg)');
                    console.log('Closing menu');
                } else {
                    // Open it
                    $submenu.slideToggle(200);
                    $this.attr('data-expanded', 'true');
                    $arrow.css('transform', 'rotate(180deg)');
                    console.log('Opening menu');

                    // Close other main menus (optional)
                    if (!$this.closest('.has-submenu').length) {
                        $('.static-sidebar-menu .menu-toggle').not($this).each(function() {
                            var $otherToggle = $(this);
                            var $otherSubmenu = $otherToggle.next('ul');
                            var $otherArrow = $otherToggle.find('.arrow-icon');

                            if ($otherToggle.attr('data-expanded') === 'true' &&
                                !$otherToggle.closest('.has-submenu').length) {
                                $otherSubmenu.slideUp(200);
                                $otherToggle.attr('data-expanded', 'false');
                                $otherArrow.css('transform', 'rotate(0deg)');
                            }
                        });
                    }
                }
            });

            // Don't run this again on load - already ran immediately
            // $(window).on('load', expandParentOfActiveItem);

            // Initially show already expanded menus (this might be overriding)
            $('.static-sidebar-menu .menu-toggle[data-expanded="true"]').each(function() {
                $(this).next('ul').show();
                $(this).find('.arrow-icon').css('transform', 'rotate(180deg)');
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
