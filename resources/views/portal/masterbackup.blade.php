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
                            <a class="admin-logo" href="{{ route('admin.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                </h1>
                            </a>
                        @elseif (Auth::user()->user_type == 'company')
                            <a class="admin-logo" href="{{ route('company.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                </h1>
                            </a>
                        @elseif (Auth::user()->user_type == 'manager')
                            <a class="admin-logo" href="{{ route('manager.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                </h1>
                            </a>
                        @elseif (Auth::user()->user_type == 'salesman')
                            <a class="admin-logo" href="{{ route('salesman.dashboard') }}">
                                <h1>
                                    <img style="width: 205px;" alt="logo"
                                        src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                        class="toggle-none hidden-xs">
                                    <img style="width: 45px;display:none;padding-top: 10px;" alt="logo"
                                        src="https://i.ibb.co/Fq5kfj8n/imageasdasdasdasd.png" class="for-coll">
                                </h1>
                            </a>
                        @else
                            <a class="admin-logo" href="{{ route('client.dashboard') }}">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-chevron-left ">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                </button>
                            </a>
                        </div>
                        <div class="left-nav-collapsed">
                            <a href="#" class="nav-collapsed">

                                <button class="custom-icon-side-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-chevron-left ">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                </button>

                                {{-- <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M20 3H4C2.89543 3 2 3.89543 2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5C22 3.89543 21.1046 3 20 3Z"
                                        stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path d="M9 3V21" stroke="#ccc" stroke-width="2"></path>
                                </svg> --}}
                            </a>
                        </div>
                        <ul class="list-inline top-right-nav">
                            <li class="dropdown avtar-dropdown">
                                <a class="dropdown-toggle  cust-dropdown-toggle" data-toggle="dropdown" href="#">
                                    <img alt="" class="rounded-circle"
                                        src="{{ !empty(currentUser()->image_url) ? Storage::url(currentUser()->image_url) : URL::to('admin/assets/img/avtar-2.png') }}"
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
                                            <a class="dropdown-item" href="{{ route('portal.update-stripe-key') }}"><i
                                                    class="icon-key"></i> Update Stripe Key</a>
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
        {{-- <div class="main-sidebar-nav default-navigation all-fonts-col">
            <div class="nano">
                <div class="nano-content sidebar-nav">
                    <div class="card-body border-bottom text-center nav-profile">
                        <div class="notify setpos"> <span class="heartbit"></span> <span class="point"></span> </div>
                        <img alt="profile" class="margin-b-10" src="{{ currentUser()->image_url }}" width="80">
                        <p class="lead margin-b-0 toggle-none">{{ currentUser()->name }}</p>
                        <p class="text-muted mv-0 toggle-none">Welcome</p>
                    </div>
                    <ul class="metisMenu nav flex-column gap-4" id="menu">
                        <li class="nav-heading" style="display:none;"><span>MODULE</span></li>
                        <li data-type="parent" class="nav-item">
                            @if (Auth::user()->user_type == 'admin')
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                            @elseif (Auth::user()->user_type == 'company')
                            <a class="nav-link" href="{{ route('company.dashboard') }}">
                                <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                            @elseif (Auth::user()->user_type == 'manager')
                            <a class="nav-link" href="{{ route('manager.dashboard') }}">
                                <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                            @elseif (Auth::user()->user_type == 'salesman')
                            <a class="nav-link" href="{{ route('salesman.dashboard') }}">
                                <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                            @else
                            <a class="nav-link" href="{{ route('client.dashboard') }}">
                                <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                            @endif
                        </li>
                        @if (Auth::user()->user_type == 'company')
                        <li data-type="parent" class="nav-item">
                            <a class="nav-link" href="{{ route('event-calander') }}">
                                <i class="fas fa-calendar"></i><span class="toggle-none"> Event Calendar</span>
                            </a>
                        </li>
                        @endif
                        @if (count($cmsModules))
                        @foreach ($cmsModules as $modules)
                        @php
                        $isActiveParent = request()->routeIs($modules->route_name);
                        $isActiveChild = collect($modules->child)
                        ->pluck('route_name')
                        ->contains(fn($route) => request()->routeIs($route));
                        @endphp

                        @if (count($modules->child))
                        <li data-type="child" class="nav-item {{ $isActiveChild ? 'active' : '' }}">
                            <a class="nav-link {{ $isActiveChild ? 'active parent-sub' : '' }}"
                                href="javascript:void(0);" aria-expanded="{{ $isActiveChild ? 'true' : 'false' }}">
                                <i class="{{ $modules->icon }}"></i>
                                <span class="toggle-none">
                                    {{ $modules->name }}
                                </span>
                                <i class="fa arrow cust-arrw"></i>
                            </a>
                            <ul class="nav-second-level nav flex-column"
                                aria-expanded="{{ $isActiveChild ? 'true' : 'false' }}">
                                @foreach ($modules->child as $childModules)
                                <li class="nav-item cust-mark-parent">
                                    <a class="nav-link cust-mark {{ request()->routeIs($childModules->route_name) ? 'active' : '' }}"
                                        href="{{ route($childModules->route_name) }}">
                                        {{ $childModules->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li data-type="parent" class="nav-item {{ $isActiveParent ? 'active' : '' }}">
                            <a class="nav-link {{ $isActiveParent ? 'active' : '' }}"
                                href="{{ route($modules->route_name) }}">
                                <i class="{{ $modules->icon }}"></i>
                                <span class="toggle-none">{{ $modules->name }}</span>
                            </a>
                        </li>
                        @endif
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div> --}}

        <div class="main-sidebar-nav default-navigation all-fonts-col">
            <div class="nano">
                <div class="nano-content sidebar-nav" style="display: flex; flex-direction: column; height: 100%;">
                    {{-- <div class="card-body border-bottom text-center nav-profile">
                        <div class="notify setpos"> <span class="heartbit"></span> <span class="point"></span> </div>
                        <img alt="profile" class="margin-b-10" src="{{ currentUser()->image_url }}" width="80">
                        <p class="lead margin-b-0 toggle-none">{{ currentUser()->name }}</p>
                        <p class="text-muted mv-0 toggle-none">Welcome</p>
                    </div> --}}

                    <!-- Menu content - flex-grow दिया है ताकि बाकी space ले ले -->
                    <div style="flex-grow: 1; overflow-y: auto;">
                        <ul class="metisMenu nav flex-column gap-4" id="menu">
                            <li class="nav-heading" style="display:none;"><span>MODULE</span></li>
                            <li data-type="parent" class="nav-item">
                                @if (Auth::user()->user_type == 'admin')
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                                    </a>
                                @elseif (Auth::user()->user_type == 'company')
                                    <a class="nav-link" href="{{ route('company.dashboard') }}">
                                        <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                                    </a>
                                @elseif (Auth::user()->user_type == 'manager')
                                    <a class="nav-link" href="{{ route('manager.dashboard') }}">
                                        <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                                    </a>
                                @elseif (Auth::user()->user_type == 'salesman')
                                    <a class="nav-link" href="{{ route('salesman.dashboard') }}">
                                        <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                                    </a>
                                @else
                                    <a class="nav-link" href="{{ route('client.dashboard') }}">
                                        <i class="grid-icon"></i> <span class="toggle-none">Dashboard</span>
                                    </a>
                                @endif
                            </li>
                            @if (Auth::user()->user_type == 'company')
                                <li data-type="parent" class="nav-item">
                                    <a class="nav-link" href="{{ route('event-calander') }}">
                                        <i class="fas fa-calendar"></i><span class="toggle-none"> Event
                                            Calendar</span>
                                    </a>
                                </li>
                            @endif
                            @if (count($cmsModules))
                                @foreach ($cmsModules as $modules)
                                    @php
                                        $isActiveParent = request()->routeIs($modules->route_name);
                                        $isActiveChild = collect($modules->child)
                                            ->pluck('route_name')
                                            ->contains(fn($route) => request()->routeIs($route));
                                    @endphp

                                    @if (count($modules->child))
                                        <li data-type="child" class="nav-item {{ $isActiveChild ? 'active' : '' }}">
                                            <a class="nav-link {{ $isActiveChild ? 'active parent-sub' : '' }}"
                                                href="javascript:void(0);" aria-expanded="{{ $isActiveChild ? 'true' : 'false' }}">
                                                <i class="{{ $modules->icon }}"></i>
                                                <span class="toggle-none">
                                                    {{ $modules->name }}
                                                </span>
                                                <i class="fa arrow cust-arrw"></i>
                                            </a>
                                            <ul class="nav-second-level nav flex-column"
                                                aria-expanded="{{ $isActiveChild ? 'true' : 'false' }}">
                                                @foreach ($modules->child as $childModules)
                                                    <li class="nav-item cust-mark-parent">
                                                        <a class="nav-link cust-mark {{ request()->routeIs($childModules->route_name) ? 'active' : '' }}"
                                                            href="{{ route($childModules->route_name) }}">
                                                            {{ $childModules->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li data-type="parent" class="nav-item {{ $isActiveParent ? 'active' : '' }}">
                                            <a class="nav-link {{ $isActiveParent ? 'active' : '' }}"
                                                href="{{ route($modules->route_name) }}">
                                                <i class="{{ $modules->icon }}"></i>
                                                <span class="toggle-none">{{ $modules->name }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
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
                    @if (empty(Auth::user()->test_publishable_key) && empty(Auth::user()->test_secret_key) &&
                    (empty(Auth::user()->live_publishable_key) && empty(Auth::user()->live_secret_key)))
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
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="{{ asset('admin/assets/js/custom.js') }}"></script>
    <script src="{{ asset('admin/assets/js/admin.js') }}"></script>

    <script>
        // Page load complete hone par skeleton hide karo
        document.addEventListener('DOMContentLoaded', function () {
            // Wait for all resources to load
            window.addEventListener('load', function () {
                setTimeout(function () {
                    const skeletonLoader = document.getElementById('skeletonLoader');
                    const mainContent = document.getElementById('mainContent');

                    if (skeletonLoader && mainContent) {
                        // Skeleton ko hide karo
                        skeletonLoader.classList.add('hidden');

                        // Main content ko show karo
                        mainContent.classList.remove('content-hidden');
                        mainContent.classList.add('content-visible');

                        // Skeleton ko completely remove karo after transition
                        setTimeout(function () {
                            skeletonLoader.style.display = 'none';
                        }, 300);
                    }
                }, 1000); // Minimum 500ms ke liye loader dikhaye
            });

            // Fallback: Agar load event na fire ho to bhi skeleton hide ho jaye
            setTimeout(function () {
                const skeletonLoader = document.getElementById('skeletonLoader');
                const mainContent = document.getElementById('mainContent');

                if (skeletonLoader && mainContent && !skeletonLoader.classList.contains('hidden')) {
                    skeletonLoader.classList.add('hidden');
                    mainContent.classList.remove('content-hidden');
                    mainContent.classList.add('content-visible');

                    setTimeout(function () {
                        skeletonLoader.style.display = 'none';
                    }, 300);
                }
            }, 3000); // Maximum 3 seconds ke baad
        });

        // Browser back/forward navigation ke liye bhi
        window.addEventListener('pageshow', function (event) {
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
    </script>

    @stack('scripts')
</body>

</html>