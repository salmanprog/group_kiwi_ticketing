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
            <div class="card-header">Calendar View</div>
            <div class="card-body p-4">
                <div id="invoice-calendar"></div>

                <div class="calendar-legend mt-4">
                    <span class="legend-item"><span class="legend-color contracts"></span> Contracts</span>
                    <span class="legend-item"><span class="legend-color estimates"></span> Estimates</span>
                </div>
            </div>
        </div>
    </div>

    @include('portal.footer')
</section>

<!-- Contracts Modal -->
<div class="modal fade" id="contractsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl for-fonts-css">
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

<!-- Estimates Modal -->
<div class="modal fade" id="estimatesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl for-fonts-css">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Estimates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Estimate No</th>
                                <th>Client Name</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="estimatesList"></tbody>
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
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('invoice-calendar');

    const contracts = @json($contracts);
    const estimates = @json($estimates);

    // Group by date
    let grouped = {};

    contracts.forEach(c => {
        if(!c.event_date) return;
        if(!grouped[c.event_date]) grouped[c.event_date] = {contracts: [], estimates: []};
        grouped[c.event_date].contracts.push(c);
    });

    estimates.forEach(e => {
        if(!e.event_date) return;
        if(!grouped[e.event_date]) grouped[e.event_date] = {contracts: [], estimates: []};
        grouped[e.event_date].estimates.push(e);
    });

    // Create separate events
    let events = [];
    Object.keys(grouped).forEach(date => {
        const cCount = grouped[date].contracts.length;
        const eCount = grouped[date].estimates.length;

        if(cCount > 0){
            events.push({
                title: `Contracts: ${cCount}`,
                start: date,
                color: 'blue',
                extendedProps: {
                    type: 'contract',
                    items: grouped[date].contracts
                }
            });
        }

        if(eCount > 0){
            events.push({
                title: `Estimates: ${eCount}`,
                start: date,
                color: 'green',
                extendedProps: {
                    type: 'estimate',
                    items: grouped[date].estimates
                }
            });
        }
    });

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: '80vh',
        events: events,
        eventClick: function(info){
            info.jsEvent.preventDefault();
            const props = info.event.extendedProps;

            if(props.type === 'contract'){
                openContractsModal(props.items, info.event.startStr);
            } else if(props.type === 'estimate'){
                openEstimatesModal(props.items, info.event.startStr);
            }
        }
    });

    calendar.render();
});

// Contracts Modal
function openContractsModal(contracts, date){
    const list = document.getElementById('contractsList');
    list.innerHTML = '';
    document.querySelector('#contractsModal .modal-title').innerText = `Contracts on ${date}`;

    if(contracts.length === 0){
        list.innerHTML = `<tr><td colspan="8" class="text-center text-muted">No contracts found</td></tr>`;
    }

    contracts.forEach((contract, index) => {
        let totalQty = 0, totalPrice = 0;
        let estimate = contract.userestimates;
        if(estimate && estimate.items){
            estimate.items.forEach(item => {
                totalQty += parseFloat(item.quantity || 0);
                totalPrice += parseFloat(item.total_price || 0);
            });
        }

        let badge = 'secondary';
        if(contract.is_accept === 'accepted') badge = 'primary';
        if(contract.is_accept === 'rejected') badge = 'danger';
        if(contract.is_accept === 'pending') badge = 'success';

        let url = `{{ route('contract.show', ':slug') }}`.replace(':slug', contract.slug);

        list.innerHTML += `
            <tr>
                <td>${index+1}</td>
                <td>${contract.contract_number}</td>
                <td>${contract.organization?.name || ''}</td>
                <td>${totalQty}</td>
                <td>${totalPrice}</td>
                <td>${contract.organization?.size || ''}</td>
                <td><span class="badge bg-${badge}">${contract.is_accept}</span></td>
                <td class="text-end"><a href="${url}" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        `;
    });

    new bootstrap.Modal(document.getElementById('contractsModal')).show();
}

// Estimates Modal
function openEstimatesModal(estimates, date){
    const list = document.getElementById('estimatesList');
    list.innerHTML = '';
    document.querySelector('#estimatesModal .modal-title').innerText = `Estimates on ${date}`;

    if(estimates.length === 0){
        list.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No estimates found</td></tr>`;
    }

    estimates.forEach((estimate, index) => {
        let badge = 'secondary';
        if(estimate.status === 'accepted') badge = 'primary';
        if(estimate.status === 'rejected') badge = 'danger';
        if(estimate.status === 'pending') badge = 'success';
        let url = `{{ route('estimate.show', ['estimate' => ':slug']) }}`.replace(':slug', estimate.slug);
        list.innerHTML += `
            <tr>
                <td>${index+1}</td>
                <td>${estimate.estimate_number}</td>
                <td>${estimate.client?.name || ''}</td>
                <td>$${estimate.total || 0}</td>
                <td><span class="badge bg-${badge}">${estimate.status}</span></td>
                <td class="text-end">
                    <a href="${url}"
                    class="btn btn-sm btn-outline-primary"
                    target="_blank">
                    View
                    </a>
                </td>
            </tr>
        `;
    });

    new bootstrap.Modal(document.getElementById('estimatesModal')).show();
}
</script>
@endpush

<style>
.main-content { background: #f8faf9; font-family: "Poppins", sans-serif; min-height: 100vh; padding: 15px; padding-top: 90px; }
.for-fonts-css { font-family: "Poppins", sans-serif; }
.card { border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
.calendar-legend { display: flex; gap: 20px; }
.legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; }
.legend-color.contracts { width: 16px; height: 16px; border-radius: 4px; background: blue; }
.legend-color.estimates { width: 16px; height: 16px; border-radius: 4px; background: green; }
#invoice-calendar { background: #fff; border-radius: 10px; padding: 10px; }
</style>
@endsection
