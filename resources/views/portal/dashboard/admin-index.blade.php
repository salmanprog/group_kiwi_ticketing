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
                <div class="col-md-3">
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
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Line Chart -->
        <div id="line_chart_container" class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Monthly User Registrations ({{ now()->year }})</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesLineChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div id="pie_chart_container" class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User Types Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="userPieChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>


        @include('portal.footer')
    </section>

    @push('scripts')
        <script src="{{ asset('admin/assets/lib/chartjs/chart.min.js') }}"></script>
        <script>
            // Dynamic Line Chart from backend
            const ctx = document.getElementById('salesLineChart').getContext('2d');
            const salesLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($line_chart['labels']),
                    datasets: [{
                        label: 'User Registrations',
                        data: @json($line_chart['data']),
                        fill: true,
                        backgroundColor: 'rgba(0,123,255,0.1)',
                        borderColor: '#007bff',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <script>
            // Pie Chart for User Types
            const pieCtx = document.getElementById('userPieChart').getContext('2d');
            const userPieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: @json($pie_chart['labels']),
                    datasets: [{
                        data: @json($pie_chart['data']),
                        backgroundColor: [
                            '#007bff', '#28a745', '#ffc107', '#dc3545', '#6610f2', '#20c997'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
