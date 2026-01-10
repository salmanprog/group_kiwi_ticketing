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
    {{-- <link rel="icon" type="image/png" sizes="16x16" href="{{ appSetting('application_setting','favicon')  }}"> --}}
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
        .top-bar.primary-top-bar {
            background: #ffffff !important;
            border-bottom: 1px solid #dee2e6;
        }

        .nav-link.active {
            color: #9FC241 !important;
            font-weight: 600;
        }

        .nav-item.active>.nav-link {
            color: #9FC241;
        }
    </style>
</head>

<body>
    <div id="overlay"></div>
    <div class="top-bar primary-top-bar">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    @if (Auth::user()->user_type == 'admin')
                        <a class="admin-logo" href="{{ route('admin.dashboard') }}">
                            <h1>
                                {{-- <img style="width: 75px;" alt="logo" src="{{ appSetting('application_setting','logo') }}" class="toggle-none hidden-xs"> --}}
                                <img style="width: 205px;" alt="logo"
                                    src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                    class="toggle-none hidden-xs">
                            </h1>
                        </a>
                    @elseif (Auth::user()->user_type == 'company')
                        <a class="admin-logo" href="{{ route('company.dashboard') }}">
                            <h1>
                                {{-- <img style="width: 75px;" alt="logo" src="{{ appSetting('application_setting','logo') }}" class="toggle-none hidden-xs"> --}}
                                <img style="width: 205px;" alt="logo"
                                    src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                    class="toggle-none hidden-xs">
                            </h1>
                        </a>
                    @elseif (Auth::user()->user_type == 'manager')
                        <a class="admin-logo" href="{{ route('manager.dashboard') }}">
                            <h1>
                                {{-- <img style="width: 75px;" alt="logo" src="{{ appSetting('application_setting','logo') }}" class="toggle-none hidden-xs"> --}}
                                <img style="width: 205px;" alt="logo"
                                    src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                    class="toggle-none hidden-xs">
                            </h1>
                        </a>
                    @elseif (Auth::user()->user_type == 'salesman')
                        <a class="admin-logo" href="{{ route('salesman.dashboard') }}">
                            <h1>
                                {{-- <img style="width: 75px;" alt="logo" src="{{ appSetting('application_setting','logo') }}" class="toggle-none hidden-xs"> --}}
                                <img style="width: 205px;" alt="logo"
                                    src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                    class="toggle-none hidden-xs">
                            </h1>
                        </a>
                    @else
                        <a class="admin-logo" href="{{ route('client.dashboard') }}">
                            <h1>
                                {{-- <img style="width: 75px;" alt="logo" src="{{ appSetting('application_setting','logo') }}" class="toggle-none hidden-xs"> --}}
                                <img style="width: 205px;" alt="logo"
                                    src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                    class="toggle-none hidden-xs">
                            </h1>
                        </a>
                    @endif
                    <div class="left-nav-toggle">
                        <a href="#" class="nav-collapse"><i class="fa fa-bars"></i></a>
                    </div>
                    <div class="left-nav-collapsed">
                        <a href="#" class="nav-collapsed">
                            {{-- <i class="fa fa-bars"></i> --}}
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M20 3H4C2.89543 3 2 3.89543 2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5C22 3.89543 21.1046 3 20 3Z"
                                    stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path d="M9 3V21" stroke="#ccc" stroke-width="2"></path>
                            </svg>
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
    <div class="main-sidebar-nav default-navigation">
        <div class="nano">
            <div class="nano-content sidebar-nav">
                <div class="card-body border-bottom text-center nav-profile">
                    <div class="notify setpos"> <span class="heartbit"></span> <span class="point"></span> </div>
                    <img alt="profile" class="margin-b-10" src="{{ currentUser()->image_url }}" width="80">
                    <p class="lead margin-b-0 toggle-none">{{ currentUser()->name }}</p>
                    <p class="text-muted mv-0 toggle-none">Welcome</p>
                </div>
                <ul class="metisMenu nav flex-column" id="menu">
                    <li class="nav-heading"><span>MODULE</span></li>
                    <li data-type="parent" class="nav-item">
                        @if (Auth::user()->user_type == 'admin')
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fa fa-home"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                        @elseif (Auth::user()->user_type == 'company')
                            <a class="nav-link" href="{{ route('company.dashboard') }}">
                                <i class="fa fa-home"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                        @elseif (Auth::user()->user_type == 'manager')
                            <a class="nav-link" href="{{ route('manager.dashboard') }}">
                                <i class="fa fa-home"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                        @elseif (Auth::user()->user_type == 'salesman')
                            <a class="nav-link" href="{{ route('salesman.dashboard') }}">
                                <i class="fa fa-home"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                        @else
                            <a class="nav-link" href="{{ route('client.dashboard') }}">
                                <i class="fa fa-home"></i> <span class="toggle-none">Dashboard</span>
                            </a>
                        @endif
                    </li>
                    @if (Auth::user()->user_type == 'company')
                        <li data-type="parent" class="nav-item">
                            <a class="nav-link" href="{{ route('event-calander') }}">
                                <i class="fas fa-calendar"></i> Event Calendar
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
                                    <a class="nav-link {{ $isActiveChild ? 'active' : '' }}"
                                        href="javascript:void(0);"
                                        aria-expanded="{{ $isActiveChild ? 'true' : 'false' }}">
                                        <i class="{{ $modules->icon }}"></i>
                                        <span class="toggle-none">
                                            {{ $modules->name }} <span class="fa arrow"></span>
                                        </span>
                                    </a>
                                    <ul class="nav-second-level nav flex-column"
                                        aria-expanded="{{ $isActiveChild ? 'true' : 'false' }}">
                                        @foreach ($modules->child as $childModules)
                                            <li class="nav-item">
                                                <a class="nav-link {{ request()->routeIs($childModules->route_name) ? 'active' : '' }}"
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
    </div>
    <div class="main-header-wrapper-for-fixed">
        <div class="row page-header align-items-center justify-content-between cust-p">
            <div class="col-lg-6">
                <h2>{{ $page_title ?? '' }}</h2>
            </div>
            <div class="col-lg-6">
                @if (Auth::user()->user_type == 'company')
                    @if (empty(Auth::user()->test_publishable_key) &&
                            empty(Auth::user()->test_secret_key) &&
                            (empty(Auth::user()->live_publishable_key) && empty(Auth::user()->live_secret_key)))
                        <strong class="text-danger">Please update Stripe key</strong>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @yield('content')
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
    @stack('scripts')
</body>

</html>
