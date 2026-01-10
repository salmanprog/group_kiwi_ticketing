@extends('portal.master')

@section('content')

    @push('stylesheets')
        <link href="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/assets/lib/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endpush

    <section class="main-content">
        <div class="row">
            <div class="card mt-4 w-100">
                <div class="card-header">
                    Calendar View
                </div>

                <div class="card-body p-4">
                    <div id="invoice-calendar"></div>

                    <!-- Legend -->
                    <div class="calendar-legend mt-4">
                        <span class="legend-item"><span class="legend-color accepted"></span> Accepted</span>
                        <span class="legend-item"><span class="legend-color rejected"></span> Rejected</span>
                        <span class="legend-item"><span class="legend-color pending"></span> Pending</span>
                    </div>
                </div>
            </div>
        </div>

        @include('portal.footer')
    </section>

    <!-- MODAL -->
    <div class="modal fade" id="contractsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contracts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Contract No</th>
                                    <th>Name</th>
                                    <th>Total Tickets</th>
                                    <th>Total Price</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="contractsList"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const calendarEl = document.getElementById('invoice-calendar');
                const contracts = @json($contracts);

                // GROUP CONTRACTS BY DATE
                let grouped = {};

                contracts.forEach(contract => {
                    if (!contract.event_date) return;

                    if (!grouped[contract.event_date]) {
                        grouped[contract.event_date] = [];
                    }

                    grouped[contract.event_date].push(contract);
                });

                // CREATE EVENTS (SHOW COUNT)
                let events = Object.keys(grouped).map(date => {
                    return {
                        title: 'Total Groups '+grouped[date].length,
                        start: date,
                        extendedProps: {
                            contracts: grouped[date],
                            date: date
                        }
                    };
                });

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: '80vh',
                    events: events,

                    eventClick: function (info) {
                        info.jsEvent.preventDefault();
                        openContractsModal(info.event.extendedProps.contracts, info.event.extendedProps.date);
                    }
                });

                calendar.render();
            });

            // MODAL FUNCTION
            // function openContractsModal(contracts, date) {

            //     const list = document.getElementById('contractsList');
            //     list.innerHTML = '';

            //     document.querySelector('.modal-title').innerText = 'Contracts on ' + date;

            //     contracts.forEach(contract => {

            //         let badge = 'secondary';
            //         if (contract.is_accept === 'accepted') badge = 'primary';
            //         if (contract.is_accept === 'rejected') badge = 'danger';
            //         if (contract.is_accept === 'pending') badge = 'success';

            //         let url = `{{ route('contract.show', ':slug') }}`.replace(':slug', contract.slug);

            //         list.innerHTML += `
            //         <li class="list-group-item d-flex justify-content-between align-items-center">
            //             <div>
            //                 <strong>#${contract.contract_number}</strong>
            //                 <span class="badge bg-${badge} ms-2">${contract.is_accept}</span>
            //             </div>
            //             <a href="${url}" class="btn btn-sm btn-outline-primary">
            //                 View
            //             </a>
            //         </li>
            //     `;
            //     });

            //     let modal = new bootstrap.Modal(document.getElementById('contractsModal'));
            //     modal.show();
            // }
            function openContractsModal(contracts, date) {

                const list = document.getElementById('contractsList');
                list.innerHTML = '';

                document.querySelector('.modal-title').innerText =
                    'Contracts on ' + date;

                if (contracts.length === 0) {
                    list.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                No contracts found
                            </td>
                        </tr>
                    `;
                }

                contracts.forEach((contract, index) => {
                    
                    let totalQty = 0;
                    let totalPrice = 0;

                    let estimate = contract.userestimates; // just one object
                    if (estimate && estimate.items) {
                        estimate.items.forEach(item => {
                            totalQty += parseFloat(item.quantity || 0);
                            totalPrice += parseFloat(item.total_price || 0);
                        });
                    }

                    let badge = 'secondary';
                    if (contract.is_accept === 'accepted') badge = 'primary';
                    if (contract.is_accept === 'rejected') badge = 'danger';
                    if (contract.is_accept === 'pending') badge = 'success';

                    let url = `{{ route('contract.show', ':slug') }}`
                        .replace(':slug', contract.slug);

                    list.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong>#${contract.contract_number}</strong></td>
                            <td><strong>${contract.organization.name}</strong></td>
                            <td><strong>${totalQty}</strong></td>
                            <td><strong>${totalPrice}</strong></td>
                            <td><strong>${contract.organization.size}</strong></td>
                            <td>
                                <span class="badge bg-${badge}">
                                    ${contract.is_accept}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="${url}" class="btn btn-sm btn-outline-primary">
                                    View Detail
                                </a>
                            </td>
                        </tr>
                    `;
                });

                new bootstrap.Modal(
                    document.getElementById('contractsModal')
                ).show();
            }
        </script>
    @endpush

    <style>
        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 15px;
            padding-top: 90px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .calendar-legend {
            display: flex;
            gap: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .legend-color.accepted {
            background: blue;
        }

        .legend-color.rejected {
            background: red;
        }

        .legend-color.pending {
            background: green;
        }

        #invoice-calendar {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
        }
    </style>

@endsection