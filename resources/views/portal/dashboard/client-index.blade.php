@extends('portal.master')

@section('content')
    @push('stylesheets')
        <link href="{{ asset('admin/assets/lib/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin/assets/lib/chart-c3/c3.min.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/assets/lib/chartjs/chartjs-sass-default.css') }}" rel="stylesheet">
    @endpush

    <section class="main-content">
        @include('portal.flash-message')
    <!-- Widgets -->
    <div class="row">
        @foreach ($widgets as $widget)
            <div class="col-md-3 for-gp-cust">
                <a href="{{ $widget['link'] }}" class="text-decoration-none">
                    <div class="card text-white cust-cardboard mb-3">
                        <div class="card-body for-card-flex">
                            <span class="for-avt-svgs">
                                <i class="{{ $widget['icon'] }} fa-2x mb-2"></i>
                            </span>
                            <div>
                                <h5 class="card-title">{{ $widget['title'] }}</h5>
                                <p class="card-text">{{ $widget['count'] }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card text-white {{ $widget['color'] }} mb-3">
                        <div class="card-body text-center">
                            <i class="{{ $widget['icon'] }} fa-2x mb-2"></i>
                            <h5 class="card-title">{{ $widget['title'] }}</h5>
                            <p class="card-text">{{ $widget['count'] }}</p>
                        </div>
                    </div> --}}
                </a>
            </div>
        @endforeach
    </div>


        @include('portal.footer')
    </section>

    @push('scripts')
        <script src="{{ asset('admin/assets/lib/chartjs/chart.min.js') }}"></script>
     
    @endpush
@endsection
