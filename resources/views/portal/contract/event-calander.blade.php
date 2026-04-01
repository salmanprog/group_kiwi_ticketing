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
                    <span class="legend-item"><span class="legend-color cabana"></span> Cabana</span>
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

<div class="modal fade" id="cabanaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl for-fonts-css">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cabana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Cabana Name</th>
                                <th>Contract</th>
                            </tr>
                        </thead>
                        <tbody id="cabanaList"></tbody>
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
    const cabana = @json($cabana);
    const visitors = @json($visitors);

    let grouped = {};

    // ✅ Initialize structure (IMPORTANT FIX)
    function initDate(date) {
        if (!grouped[date]) {
            grouped[date] = {
                contracts: [],
                estimates: [],
                cabana: [],
               visitors: [] // ✅ should be array
            };
        }
    }

    // ✅ Group Contracts
    contracts.forEach(c => {
        if (!c.event_date) return;
        initDate(c.event_date);
        grouped[c.event_date].contracts.push(c);
    });

    // ✅ Group Estimates
    estimates.forEach(e => {
        if (!e.event_date) return;
        initDate(e.event_date);
        grouped[e.event_date].estimates.push(e);
    });

    // ✅ Group Cabana (FIXED ERROR HERE)
    cabana.forEach(cab => {
        if (!cab.event_date) return;
        initDate(cab.event_date);
        grouped[cab.event_date].cabana.push(cab);
    });

  visitors.forEach(visitor => {
    if (!visitor.event_date) return;

    initDate(visitor.event_date);

    // ✅ ensure visitors is an array
    if (!Array.isArray(grouped[visitor.event_date].visitors)) {
        grouped[visitor.event_date].visitors = [];
    }

    grouped[visitor.event_date].visitors.push(visitor);

    // sum total_quantity safely
    if (!grouped[visitor.event_date].visitors_total) {
        grouped[visitor.event_date].visitors_total = 0;
    }
    grouped[visitor.event_date].visitors_total += Number(visitor.total_quantity || 0);
});

    // ✅ Create events
    let events = [];

    Object.keys(grouped).forEach(date => {
        const cCount = grouped[date].contracts.length;
        const eCount = grouped[date].estimates.length;
        const cabanaCount = grouped[date].cabana.length;
        const visitorTotal = grouped[date].visitors_total || 0;

        if (cCount > 0) {
            events.push({
                title: `Contracts: ${cCount}`,
                start: date,
                className: 'event-contract',
                extendedProps: {
                    type: 'contract',
                    items: grouped[date].contracts
                }
            });
        }

        if (eCount > 0) {
            events.push({
                title: `Estimates: ${eCount}`,
                start: date,
                className: 'event-estimate',
                extendedProps: {
                    type: 'estimate',
                    items: grouped[date].estimates
                }
            });
        }

        if (cabanaCount > 0) {
            events.push({
                title: `Cabana: ${cabanaCount}`,
                start: date,
                className: 'event-cabana',
                extendedProps: {
                    type: 'cabana',
                    items: grouped[date].cabana
                }
            });
        }
        
       if (visitorTotal > 0) {
            events.push({
                title: `Visitors: ${visitorTotal}`,
                start: date,
                className: 'event-visitor',
                extendedProps: {
                    type: 'visitor',
                    items: grouped[date].visitors
                }
            });
        }
    });

    // ✅ Init Calendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: '80vh',
        events: events,

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            const props = info.event.extendedProps;

            if (props.type === 'contract') {
                openContractsModal(props.items, info.event.startStr);
            } else if (props.type === 'estimate') {
                openEstimatesModal(props.items, info.event.startStr);
            } else if (props.type === 'cabana') {
                openCabanaModal(props.items, info.event.startStr);
            }
        }
    });

    calendar.render();
});


// ================= CONTRACT MODAL =================
function openContractsModal(contracts, date) {
    const list = document.getElementById('contractsList');
    list.innerHTML = '';

    document.querySelector('#contractsModal .modal-title')
        .innerText = `Contracts on ${date}`;

    if (contracts.length === 0) {
        list.innerHTML = `<tr><td colspan="8" class="text-center text-muted">No contracts found</td></tr>`;
        return;
    }

    contracts.forEach((contract, index) => {

        let totalQty = 0, totalPrice = 0;

        let estimate = contract.userestimates;
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

        let url = `{{ route('contract.show', ':slug') }}`.replace(':slug', contract.slug);

        list.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${contract.contract_number}</td>
                <td>${contract.organization?.name || ''}</td>
                <td>${totalQty}</td>
                <td>${totalPrice}</td>
                <td>${contract.organization?.size || ''}</td>
                <td><span class="badge bg-${badge}">${contract.is_accept}</span></td>
                <td class="text-end">
                    <a href="${url}" class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        `;
    });

    new bootstrap.Modal(document.getElementById('contractsModal')).show();
}


// ================= ESTIMATES MODAL =================
function openEstimatesModal(estimates, date) {
    const list = document.getElementById('estimatesList');
    list.innerHTML = '';

    document.querySelector('#estimatesModal .modal-title')
        .innerText = `Estimates on ${date}`;

    if (estimates.length === 0) {
        list.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No estimates found</td></tr>`;
        return;
    }

    estimates.forEach((estimate, index) => {

        let badge = 'secondary';
        if (estimate.status === 'accepted') badge = 'primary';
        if (estimate.status === 'rejected') badge = 'danger';
        if (estimate.status === 'pending') badge = 'success';

        let url = `{{ route('estimate.show', ['estimate' => ':slug']) }}`.replace(':slug', estimate.slug);

        list.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${estimate.estimate_number}</td>
                <td>${estimate.client?.name || ''}</td>
                <td>$${estimate.total || 0}</td>
                <td><span class="badge bg-${badge}">${estimate.status}</span></td>
                <td class="text-end">
                    <a href="${url}" class="btn btn-sm btn-outline-primary" target="_blank">View</a>
                </td>
            </tr>
        `;
    });

    new bootstrap.Modal(document.getElementById('estimatesModal')).show();
}


// ================= CABANA MODAL =================
function openCabanaModal(cabana, date) {
    const list = document.querySelector('#cabanaModal tbody');
    list.innerHTML = '';

    document.querySelector('#cabanaModal .modal-title')
        .innerText = `Cabana on ${date}`;

    if (cabana.length === 0) {
        list.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No cabana found</td></tr>`;
        return;
    }

    cabana.forEach((item, index) => {
        list.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.name}</td>
                <td>${item.contract_slug || ''}</td>
            </tr>
        `;
    });

    new bootstrap.Modal(document.getElementById('cabanaModal')).show();
}
</script>
@endpush

<style>
    .event-visitor {
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  padding: 2px 8px;
  border-radius: 0.375rem;
  font-size: 13px;
  font-weight: 500;
  background-color: #fee8e8;
  color: #78350f;
  border: 1px solid #fef3c7;
  margin-bottom: 6px;
}
 
.event-visitor .fc-event-title.fc-sticky {
  color: #ca2525;
}
.main-content { 
    background: #f8faf9; 
    font-family: "Poppins", sans-serif; 
    min-height: 100vh; 
    padding: 15px; 
    padding-top: 90px; 
}
.for-fonts-css { font-family: "Poppins", sans-serif; }
.card { border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }

.calendar-legend { display: flex; gap: 20px; }
.legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; }
.legend-color.contracts { width: 16px; height: 16px; border-radius: 4px; background: blue; }
.legend-color.estimates { width: 16px; height: 16px; border-radius: 4px; background: green; }
.legend-color.cabana { width: 16px; height: 16px; border-radius: 4px; background: orange; }

.for-fonts-css h5.modal-title { font-size: 18px; font-weight: 700; color: #1f2937; }
.for-fonts-css thead.table-light tr th {
    color: #6b7280;
    font-size: 12px;
}

.for-fonts-css span.badge {
    font-size: 12px;
    font-weight: 400;
    text-transform: uppercase;
}

/* Cabana badge in modal */
.for-fonts-css span.badge-cabana {
    background-color: orange;
    color: #fff;
    font-weight: 400;
    font-size: 12px;
    text-transform: uppercase;
    padding: 0.25em 0.5em;
    border-radius: 0.25rem;
}

#invoice-calendar { 
    background: #fff; 
    border-radius: 10px; 
    padding: 10px; 
}



.for-fonts-css span.badge-cabana {
    background-color: orange;
    color: #fff;
    font-weight: 400;
    font-size: 12px;
    text-transform: uppercase;
    padding: 0.25em 0.5em;
    border-radius: 0.25rem;
}


</style>
@endsection
