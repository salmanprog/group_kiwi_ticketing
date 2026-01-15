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
                    </a>
                </div>
            @endforeach
        </div>

       <div class="row">
            <!-- Line Chart -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Estimates and Contracts Created</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesLineChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>User Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="userPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>



        @include('portal.footer')
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // Line Chart
            const lineCtx = document.getElementById('salesLineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: @json($line_chart['labels']),
                    datasets: [
                        {
                            label: 'Estimates Sent',
                            data: @json($line_chart['estimates']),
                            fill: true,
                            backgroundColor: 'rgba(255, 193, 7, 0.1)', // yellow
                            borderColor: '#ffc107',
                            tension: 0.3
                        },
                        {
                            label: 'Contracts Created',
                            data: @json($line_chart['contracts']),
                            fill: true,
                            backgroundColor: 'rgba(40, 167, 69, 0.1)', // green
                            borderColor: '#28a745',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true },
                        title: {
                            display: true,
                            text: 'Estimates and Contracts Created (Monthly)'
                        }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Pie Chart
            const pieCtx = document.getElementById('userPieChart').getContext('2d');
            const labels = @json($pie_chart['labels']);
            const data = @json($pie_chart['data']);

            // Assign colors to each user type
            const typeColors = {
                'Companies': '#28a745',   // green
                'Managers': '#ffc107',    // yellow
                'Clients': '#6c757d',     // gray
                'Salesmen': '#dc3545'     // red
            };
            const backgroundColors = labels.map(l => typeColors[l] ?? '#007bff');

            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        </script>
    @endpush
@endsection
