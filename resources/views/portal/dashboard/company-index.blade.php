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
                <div class="col-lg col-md-4 col-sm-6">
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

        <!-- Charts Row 1 -->
        <div class="row font-cls">
            <!-- Contract Registrations Line Chart -->
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0 graph-heading">
                            Estimate and Contracts Created ({{ now()->year }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="contractCreationChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Estimate Status Pie Chart -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Estimate Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="estimatePieChart"></canvas>
                    </div>
                </div>
            
                <div class="card">
                    <div class="card-header">
                        <h5>Contract Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="contractStatusChart"></canvas>
                    </div>
                </div>
            </div>

            
        </div>

        <!-- Charts Row 2 -->
        {{-- <div class="row font-cls">
       
    </div> --}}

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
                    datasets: @json($line_chart['datasets'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    tension: 0.3
                }
            });
            /* =======================
            Graph 2: Estimate Status Pie
            ======================= */
            const estimateLabels = @json($estimate_chart['labels']);
            const estimateData   = @json($estimate_chart['data']);

            const estimateColors = estimateLabels.map(label => {
                switch (label.toLowerCase()) {
                    case 'approved': return '#28a745';
                    case 'sent': return '#ffc107';
                    case 'draft': return '#6c757d';
                    case 'rejected': return '#dc3545';
                    default: return '#adb5bd';
                }
            });

            new Chart(document.getElementById('estimatePieChart'), {
                type: 'pie',
                data: {
                    labels: estimateLabels,
                    datasets: [{
                        data: estimateData,
                        backgroundColor: estimateColors
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            /* =======================
            Graph 3: Contract Status Pie
            ======================= */
            new Chart(document.getElementById('contractStatusChart'), {
                type: 'pie',
                data: {
                    labels: @json($contract_chart['labels']),
                    datasets: [{
                        data: @json($contract_chart['data']),
                        backgroundColor: ['#ffc107', '#28a745', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } }
                }
            });
        </script>
    @endpush
@endsection
