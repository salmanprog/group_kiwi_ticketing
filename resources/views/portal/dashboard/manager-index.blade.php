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
                            <span class="card-body for-card-flex">
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

    <!-- Charts Row 1 -->
    <div class="row">
        <!-- Contract Registrations Line Chart -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0 graph-heading">Contracts Created ({{ now()->year }})</h5></div>
                <div class="card-body">
                    <canvas id="contractCreationChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Estimate Status Pie Chart -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Estimate Status Distribution</h5></div>
                <div class="card-body">
                    <canvas id="estimatePieChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row">
        <!-- Contract Status Line Chart -->
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Contract Status Distribution</h5></div>
                <div class="card-body">
                    <canvas id="contractStatusChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    @include('portal.footer')
</section>

@push('scripts')
    <script src="{{ asset('admin/assets/lib/chartjs/chart.min.js') }}"></script>

    <script>
        // Contract Creation Line Chart
        new Chart(document.getElementById('contractCreationChart'), {
            type: 'line',
            data: {
                labels: @json($line_chart['labels']),
                datasets: [{
                    label: 'Contracts Created',
                    data: @json($line_chart['data']),
                    fill: true,
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderColor: '#007bff',
                    tension: 0.3
                }]
            },
            options: { responsive: true }
        });

        // Estimate Status Pie Chart
        new Chart(document.getElementById('estimatePieChart'), {
            type: 'pie',
            data: {
                labels: @json($estimate_chart['labels']),
                datasets: [{
                    data: @json($estimate_chart['data']),
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Contract Status Monthly Line Chart
        new Chart(document.getElementById('contractStatusChart'), {
            type: 'pie',
            data: {
                labels: @json($contract_chart['labels']),
                datasets: [{
                    data: @json($contract_chart['data']),
                    backgroundColor: [ '#ffc107','#28a745','#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    </script>
@endpush

@endsection
