@extends('portal.master')

@section('content')
    <style>
        .contract-wrapper {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: #333;
        }

        .contract-header {
            border-bottom: 2px solid #007bff;
            margin-bottom: 25px;
            padding-bottom: 15px;
        }

        .contract-title {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }

        .contract-meta {
            margin-top: 10px;
            font-size: 15px;
            color: #666;
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-weight: 600;
            background: #ffc107;
            color: #212529;
        }

        .address-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .address-box {
            flex: 1;
            min-width: 280px;
            padding: 18px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
        }

        .address-box h4 {
            margin-bottom: 10px;
            color: #007bff;
            font-size: 18px;
        }

        .contract-table,
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .contract-table th,
        .contract-table td,
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }

        .contract-table th,
        .table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .text-success {
            color: #28a745 !important;
            font-weight: 600;
        }

        .text-danger {
            color: #dc3545 !important;
            font-weight: 600;
        }

        .actions {
            margin-top: 30px;
            text-align: center;
        }

        .actions .btn {
            min-width: 140px;
            font-size: 15px;
            padding: 10px;
            border-radius: 8px;
        }

        .invoice-box {
            margin-top: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fdfdfd;
        }

        .invoice-box h5 {
            color: #007bff;
            margin-bottom: 15px;
        }

        .installment-table td,
        .installment-table th {
            text-align: center;
        }
    </style>

    <section class="main-content">
        <div class="container">
            <div class="contract-wrapper">

                <!-- Contract Header -->
                <div class="contract-header">
                    <div class="contract-title">Contract</div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="contract-meta">
                        <strong>#{{ ucfirst($record->slug) }}</strong> —
                        <span class="status">{{ strtoupper($record->status) }}</span><br>
                        <small>Start Date: {{ \Carbon\Carbon::parse($record->start_date)->format('F j, Y') }}</small><br>
                        <small>Event Date: {{ \Carbon\Carbon::parse($record->event_date)->format('F j, Y') }}</small><br>

                        @if($record->is_accept === 'accepted')
                            <small class="text-success">Client accepted this contract</small>
                        @elseif($record->is_accept === 'rejected')
                            <small class="text-danger">Client rejected this contract</small>
                        @endif
                    </div>
                </div>

                <!-- Parties Section -->
                <div class="address-section">
                    <div class="address-box">
                        <h4>Company</h4>
                        <p>
                            <strong>{{ $record->company->name }}</strong><br>
                            {{ $record->company->address }}<br>
                            Email: {{ $record->company->email }}<br>
                            Phone: {{ $record->company->mobile_no }}
                        </p>
                    </div>
                    <div class="address-box">
                        <h4>Organization</h4>
                        <p>
                            <strong>{{ $record->organization->name }}</strong><br>
                            Email: {{ $record->organization->email ?? '-' }}<br>
                            Phone: {{ $record->organization->mobile_no ?? '-' }}
                        </p>
                    </div>
                    <div class="address-box">
                        <h4>Client</h4>
                        <p>
                            <strong>{{ $record->client->name }}</strong><br>
                            Email: {{ $record->client->email ?? '-' }}<br>
                            Phone: {{ $record->client->mobile_no ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Linked Estimates -->
                <h5>Estimates Linked to this Contract</h5>
                <table class="contract-table">
                    <thead>
                        <tr>
                            <th>Estimate #</th>
                            <th>Issue Date</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($record->estimates)
                            @foreach ($record->estimates as $estimate)
                                <tr>
                                    <td>
                                        <a href="{{ route('estimate.show', $estimate->slug) }}">
                                            {{ strtoupper($estimate->slug) }}
                                        </a>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($estimate->issue_date)->format('F j, Y') }}</td>
                                    <td>
                                        {{ strtoupper($estimate->status) }}
                                        @if($estimate->is_adjusted) <em>(Adjusted)</em> @endif
                                    </td>
                                    <td>${{ number_format($estimate->total, 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center text-muted"><em>No estimates yet.</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                @if (Auth::user()->user_type == 'company')
                    <div class="mt-3 text-end">
                        <a href="{{ route('estimate.create', ['contract' => encrypt($record->slug)]) }}"
                           class="btn btn-primary">+ Add New Estimate</a>
                    </div>
                @endif

                <!-- Invoices -->
                <div class="card shadow-sm mb-4 mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Invoices Generated (Linked with Estimates)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Estimate Ref</th>
                                        <th>Issue Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        @if(Auth::user()->user_type != 'client')
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($record->invoices as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('invoice.show', $invoice->slug) }}" class="fw-bold text-decoration-none">
                                                    {{ strtoupper($invoice->slug) }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('estimate.show', $invoice->estimate_slug) }}" class="text-muted">
                                                    {{ strtoupper($invoice->estimate_slug) }}
                                                </a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('F j, Y') }}</td>
                                            <td>
                                                @switch($invoice->status)
                                                    @case('paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @break
                                                    @case('unpaid')
                                                    @case('partial')
                                                        <span class="badge bg-warning text-dark">{{ ucfirst($invoice->status) }}</span>
                                                    @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td class="fw-semibold">${{ number_format($invoice->total, 2) }}</td>
                                            @if(Auth::user()->user_type != 'client')
                                                <td>
                                                    @if(in_array($invoice->status, ['paid', 'partial']))
                                                        <a href="{{ route('contract.add-credit-note', $invoice->slug) }}" class="btn btn-sm btn-outline-primary">Credit Note</a>
                                                    @else
                                                        <span class="text-muted">--</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>

                                        {{-- Installment Plan --}}
                                        @if ($invoice->installmentPlan)
                                            <tr>
                                                <td colspan="6">
                                                    <div class="mt-3">
                                                        <h6 class="fw-bold">Installment Schedule</h6>
                                                        <table class="table table-sm table-striped">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Due Date</th>
                                                                    <th>Amount</th>
                                                                    <th>Status</th>
                                                                    <th>Paid On</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($invoice->installmentPlan->payments as $installment)
                                                                    <tr>
                                                                        <td>{{ $installment->installment_number }}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('F j, Y') }}</td>
                                                                        <td>${{ number_format($installment->amount, 2) }}</td>
                                                                        <td>
                                                                            @if ($installment->is_paid)
                                                                                <span class="badge bg-success">Paid</span>
                                                                            @elseif ($installment->status === 'cancelled')
                                                                                <span class="badge bg-danger">Cancelled</span>
                                                                            @else
                                                                                <span class="badge bg-warning text-dark">Unpaid</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $installment->paid_at ? \Carbon\Carbon::parse($installment->paid_at)->format('F j, Y') : '-' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- Credit Notes --}}
                                        @if ($invoice->creditNotes && $invoice->creditNotes->count())
                                            <tr>
                                                <td colspan="6">
                                                    <div class="mt-3">
                                                        <h6 class="fw-bold">Credit Notes</h6>
                                                        <table class="table table-sm table-hover">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Invoice</th>
                                                                    <th>Amount</th>
                                                                    <th>Reason</th>
                                                                    <th>Status</th>
                                                                    <th>Created At</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($invoice->creditNotes as $note)
                                                                    <tr>
                                                                        <td>{{ $note->id }}</td>
                                                                        <td>
                                                                            <a href="{{ route('invoice.show', $invoice->slug) }}" class="text-decoration-none">
                                                                                {{ strtoupper($invoice->slug) }}
                                                                            </a>
                                                                        </td>
                                                                        <td>${{ number_format($note->amount, 2) }}</td>
                                                                        <td>{{ $note->reason ?? '-' }}</td>
                                                                        <td>
                                                                            @if ($note->status === 'open')
                                                                                <span class="badge bg-warning text-dark">Open</span>
                                                                            @else
                                                                                <span class="badge bg-success">Settled</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $note->created_at->format('F j, Y') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <em>No invoices yet. Invoice is still in draft.</em>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Terms & Notes -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold">Terms</h5>
                        <p>{{ $record->terms }}</p>

                        <h5 class="fw-bold mt-3">Notes</h5>
                        <p>{{ $record->notes }}</p>
                    </div>
                </div>

                <!-- Company Edit Section -->
                @if (Auth::user()->user_type == 'company')
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <form action="{{ route('contract.update-contract', $record->slug) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Event Date</label>
                                    <input type="date" class="form-control" value="{{ $record->event_date }}" name="event_date">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Terms</label>
                                    <textarea name="terms" rows="4" class="form-control">{{ $record->terms }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Notes</label>
                                    <textarea name="notes" rows="3" class="form-control">{{ $record->notes }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Client Actions -->
                @if (Auth::user()->user_type == 'client')
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            @if ($record->is_accept == 'pending')
                                <div class="d-flex justify-content-center gap-3">
                                    <form action="{{ route('contract.accept', $record->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Accept Contract</button>
                                    </form>
                                    <form action="{{ route('contract.reject', $record->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Reject Contract</button>
                                    </form>
                                </div>
                            @else
                                @if ($record->is_accept == 'accepted')
                                    <span class="badge bg-success p-2">Accepted</span>
                                @else
                                    <span class="badge bg-danger p-2">Rejected</span>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection
