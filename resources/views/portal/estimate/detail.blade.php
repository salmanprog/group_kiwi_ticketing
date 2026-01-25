@extends('portal.master')
@section('content')

    <style>
        /* ===============================
        ESTIMATE PROFESSIONAL THEME
        =============================== */

        body {
            background: #f3f4f6;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1f2937;
        }

        /* Main Wrapper */
        .estimate-wrapper {
            background: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            margin-bottom: 40px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 20px;
        }

        .action-buttons .btn {
            font-weight: 500;
            padding: 8px 16px;
        }

        /* Header */
        .estimate-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .estimate-title {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
        }

        .estimate-meta {
            text-align: right;
            font-size: 14px;
        }

        .estimate-number {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        /* Status Pills */
        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .status.sent { background: #e0f2fe; color: #0369a1; }
        .status.accepted { background: #dcfce7; color: #166534; }
        .status.rejected { background: #fee2e2; color: #991b1b; }
        .status.revised { background: #fef3c7; color: #92400e; }

        /* Edit link */
        .edit-link {
            display: inline-block;
            margin-top: 6px;
            color: #2563eb;
            font-weight: 500;
            text-decoration: none;
        }

        .edit-link:hover {
            text-decoration: underline;
        }

        /* Address Section */
        .address-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }

        .address-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            background: #fafafa;
        }

        .address-box h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .address-box p {
            font-size: 14px;
            margin-bottom: 0;
            line-height: 1.6;
        }

        /* Product Table */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 24px;
        }

        .product-table thead {
            background: #f9fafb;
        }

        .product-table th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .product-table td {
            padding: 12px;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .product-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Summary Table */
        .summary-table {
            width: 320px;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .summary-table th,
        .summary-table td {
            padding: 8px 12px;
            font-size: 14px;
        }

        .summary-table th {
            color: #374151;
            font-weight: 500;
        }

        .summary-table td {
            text-align: right;
        }

        .summary-table tr:last-child {
            border-top: 2px solid #111827;
        }

        .summary-table tr:last-child th,
        .summary-table tr:last-child td {
            font-size: 16px;
            font-weight: 700;
        }

        /* Notes Section */
        .notes-section {
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            margin-top: 20px;
        }

        .notes-section h5 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        /* Installments */
        .installments-section h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .installments-section table th {
            background: #f9fafb;
            font-size: 13px;
        }

        /* Activity Section */
        .activity-section {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        .section-header {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .estimate-info {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fafafa;
        }

        .info-item label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 4px;
            display: block;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .note-box {
            background: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 16px 18px;
            border-radius: 8px;
            margin-top: 24px;
        }

        .note-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .note-content {
            font-size: 14px;
            color: #374151;
            line-height: 1.65;
        }

        .note-content p {
            margin-bottom: 8px;
        }

        .note-content p:last-child {
            margin-bottom: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .estimate-info {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .estimate-info {
                grid-template-columns: 1fr;
            }
        }

        /* Print Friendly */
        @media print {
            body {
                background: #ffffff;
            }
            .action-buttons,
            .activity-section {
                display: none;
            }
            .estimate-wrapper {
                box-shadow: none;
                padding: 0;
            }
        }

    </style>

    <section class="main-content">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="estimate-wrapper">

                    {{-- Action Buttons --}}
                    @if ($estimate->status === 'sent' || $estimate->status === 'revised')
                        @if (Auth::user()->user_type == 'client')
                            <div class="action-buttons">
                                <form action="{{ route('estimates.accept', $estimate->id) }}" method="POST"
                                    class="d-inline-block">
                                    <input type="hidden" name="slug" value="{{ $estimate->slug }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Accept & Create Invoice
                                    </button>
                                </form>

                                <form action="{{ route('estimates.reject', $estimate->id) }}" method="POST"
                                    class="d-inline-block">
                                    <input type="hidden" name="slug" value="{{ $estimate->slug }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Reject Estimate
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="action-buttons">
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                This estimate has already been {{ $estimate->status }}.
                            </p>
                        </div>
                    @endif

                    {{-- Estimate Header --}}
                    <div class="estimate-header">
                        <div class="estimate-title">
                            <i class="fas fa-file-invoice-dollar me-2"></i>
                            Estimate
                        </div>
                        <div class="estimate-meta">
                            <span class="estimate-number">#{{ $estimate->estimate_number }}</span>
                            <span class="status {{ $estimate->status }}">
                                @if ($estimate->status == 'sent')
                                    @if ($estimate->status == 'sent')
                                        New
                                    @endif
                                @else
                                    {{ strtoupper($estimate->status) }}
                                    {{ $estimate->is_adjusted == '1' ? '( + Adjusted)' : '' }}
                                @endif
                            </span>
                            <div class="text-muted">
                                <i class="fas fa-calendar me-1"></i>Date:
                                {{ \Carbon\Carbon::parse($estimate->issue_date)->format('F d, Y') }}<br>
                                <i class="fas fa-clock me-1"></i>Valid Until:
                                {{ \Carbon\Carbon::parse($estimate->valid_until)->format('F d, Y') }}
                            </div>
                            @if ($estimate->invoices)
                                @if ($estimate->invoices->status == 'unpaid' && Auth::user()->user_type != 'client')
                                    <a href="{{ route('estimate.edit', ['estimate' => $estimate->slug]) }}"
                                        class="edit-link">
                                        <i class="fas fa-pencil-alt me-1"></i>Edit
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Address Boxes --}}
                    <div class="address-section">
                        <div class="address-box">
                            <h4><i class="fas fa-building me-2"></i>From</h4>
                            <p>
                                <strong>{{ $estimate->company->name ?? 'Company Name' }}</strong><br>
                                {{ $estimate->company->address ?? 'Company Address' }}<br>
                                <i class="fas fa-envelope me-1"></i>Email: {{ $estimate->company->email ?? '-' }}<br>
                                <i class="fas fa-phone me-1"></i>Phone: {{ $estimate->company->phone ?? '-' }}
                            </p>
                        </div>

                        <div class="address-box">
                            <h4><i class="fas fa-user me-2"></i>Invoice To</h4>
                            <p>
                                <strong>{{ $estimate->organization->name ?? 'Client Name' }}</strong><br>
                                {{ $estimate->organization->address_one ?? 'Client Address' }}<br>
                                <i class="fas fa-envelope me-1"></i>Email: {{ $estimate->organization->email ?? '-' }}<br>
                                <i class="fas fa-phone me-1"></i>Phone: {{ $estimate->organization->phone ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="estimate-info">
                        <div class="info-item">
                            <label>Client</label>
                            <div class="info-value">{{ $estimate->client->name ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <label>Estimate Date</label>
                            <div class="info-value">{{ $estimate->issue_date ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <label>Event Date</label>
                            <div class="info-value">{{ $estimate->event_date ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <label>Expiry Date</label>
                            <div class="info-value">{{ $estimate->valid_until ?? '-' }}</div>
                        </div>
                    </div>


                    {{-- Product Table --}}
                    <div>
                        <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Product Details
                                    </h5>
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Product Price</th>
                                    <th>Tax (%)</th>
                                    <th>Gratuity (%)</th>
                                    <th>Price (Tax + Gratuity)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estimate->items as $item)
                                    <tr>
                                        <td>{{ $item->name ?? 'Item' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>${{ number_format($item->tax, 2) }}</td>
                                        <td>${{ number_format($item->gratuity, 2) }}</td>
                                        <td>${{ number_format($item->product_price,2) }}</td>
                                        <td>${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary Section --}}
                    <table class="summary-table">
                        <tbody>
                            <tr>
                                <th>Subtotal</th>
                                <td>${{ number_format($estimate->subtotal, 2) }}</td>
                            </tr>

                            {{-- Taxes --}}
                            @foreach ($estimate->taxes as $tax)
                                <tr>
                                    <th>{{ $tax->name }} ({{ $tax->percent }}%)</th>
                                    <td>${{ number_format(($tax->percent / 100) * $estimate->subtotal, 2) }}</td>
                                </tr>
                            @endforeach

                            {{-- Discounts --}}
                            @if ($estimate->discounts)
                                @foreach ($estimate->discounts as $discount)
                                    <tr>
                                        <th>{{ $discount->name }}</th>
                                        <td class="text-danger">– ${{ number_format($discount->value, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            <tr>
                                <th>Total</th>
                                <td class="font-weight-bold">${{ number_format($estimate->total, 2) }}</td>
                            </tr>
                            <!--
                                <tr>
                                    <th>Amount Paid</th>
                                    <td class="text-success">${{ number_format($estimate->total, 2) }}</td>
                                </tr> -->
                        </tbody>
                    </table>
                    {{-- Notes--}}
                   @if ($estimate->note)
                        <div class="notes-section note-box">
                            <h5 class="note-title">
                                <i class="fas fa-file-contract me-2"></i>
                                Note
                            </h5>
                            <div class="note-content">
                                {!! $estimate->note !!}
                            </div>
                        </div>
                    @endif
                    @if ($estimate->installments && $estimate->installments->count() > 0)
                        <div class="installments-section mt-4">
                            <h3 class="text-lg font-semibold mb-2">Payment Schedule</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Installment</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($estimate->installments as $index => $installment)
                                            <tr>
                                                <td>#{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('M d, Y') }}</td>
                                                <td class="font-weight-bold">
                                                    {{ number_format($installment->amount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    {{-- Notes and Terms --}}
                    @if ($estimate->terms_and_condition)
                        <div class="notes-section">
                            @if ($estimate->terms_and_condition)
                                <h5><i class="fas fa-sticky-note me-2"></i>Terms And Condtion</h5>
                                <p>{!! $estimate->terms_and_condition !!}</p>
                            @endif
                        </div>
                    @endif
                    

                </div>
            </div>
        </div>

        @if(Auth::user()->user_type !== 'client')
        <div class="activity-section">
            <div class="section-header">
                <i class="fas fa-history me-2"></i>Recent Activity
            </div>

            <div class="activity-table-container">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>User</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->created_at }}</td>
                                <td>{{ $log->user_name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($log->description, 60) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-details" data-bs-toggle="modal"
                                        data-bs-target="#activityModal" data-description="{{ $log->description }}"
                                        data-alldata='{{ $log->new_data }}'>
                                        View
                                    </button>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No activity logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="activityModalLabel">Activity Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <h6><strong>Description:</strong></h6>
                            <p id="modalDescription"></p>
                            <hr>

                            <div id="estimateData"></div> {{-- Dynamic content will appear here --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </section>
    <script>
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const description = this.dataset.description || 'No description available';
                const allData = this.dataset.alldata || '{}';
                document.getElementById('modalDescription').innerText = description;

                const container = document.getElementById('estimateData');
                container.innerHTML = ''; // Clear previous data

                try {
                    let data = JSON.parse(allData);
                    if (typeof data === 'string') {
                        data = JSON.parse(data); // handle double encoding
                    }

                    console.log(data);
                    const estimate = data.estimate || {};
                    const items = data.items || [];
                    const taxes = data.taxes || [];
                    const discounts = data.discounts || [];

                    // Estimate Details Section
                    let html = `
                        <div class="mb-4">
                            <h5 class="theme-text mb-3"><i class="fas fa-file-invoice"></i> Estimate Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm theme-table">
                                    <thead>
                                        <tr>
                                            <th width="30%">Field</th>
                                            <th width="70%">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td><strong>ID</strong></td><td>${estimate.id || '-'}</td></tr>
                                        <tr><td><strong>Estimate Number</strong></td><td>${estimate.estimate_number || '-'}</td></tr>
                                        <tr><td><strong>Status</strong></td><td><span class="badge theme-badge">${estimate.status || '-'}</span></td></tr>
                                        <tr><td><strong>Total Amount</strong></td><td><strong>${estimate.total || '0.00'}</strong></td></tr>
                                        <tr><td><strong>Issue Date</strong></td><td>${estimate.issue_date || '-'}</td></tr>
                                        <tr><td><strong>Valid Until</strong></td><td>${estimate.valid_until || '-'}</td></tr>
                                        <tr><td><strong>Created At</strong></td><td>${estimate.created_at || '-'}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;

                    // Items Section
                    if (items.length > 0) {
                        html += `
                            <div class="mb-4">
                                <h5 class="theme-text mb-3"><i class="fas fa-list"></i> Items (${items.length})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm theme-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${items.map(item => `
                                                            <tr>
                                                                <td>${item.name || '-'}</td>
                                                                <td>${item.quantity || '0'}</td>
                                                                <td>${item.unit || '-'}</td>
                                                                <td>${item.price || '0.00'}</td>
                                                                <td><strong>${item.total_price || '0.00'}</strong></td>
                                                            </tr>
                                                        `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    // Taxes Section
                    if (taxes.length > 0) {
                        html += `
                            <div class="mb-4">
                                <h5 class="theme-text mb-3"><i class="fas fa-percentage"></i> Taxes (${taxes.length})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm theme-table">
                                        <thead>
                                            <tr>
                                                <th>Tax Name</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${taxes.map(tax => `
                                                            <tr>
                                                                <td>${tax.name || '-'}</td>
                                                                <td>${tax.percent || '0'}%</td>
                                                            </tr>
                                                        `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    // Discounts Section
                    if (discounts.length > 0) {
                        html += `
                            <div class="mb-4">
                                <h5 class="theme-text mb-3"><i class="fas fa-tag"></i> Discounts (${discounts.length})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm theme-table">
                                        <thead>
                                            <tr>
                                                <th>Discount Name</th>
                                                <th>Value</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${discounts.map(discount => `
                                                            <tr>
                                                                <td>${discount.name || '-'}</td>
                                                                <td>${discount.value || '0'}</td>
                                                                <td><span class="badge bg-info">${discount.type || '-'}</span></td>
                                                            </tr>
                                                        `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    // Fallback if no data
                    if (!items.length && !taxes.length && !discounts.length) {
                        html += `
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No additional data found for this estimate.</p>
                            </div>
                        `;
                    }

                    container.innerHTML = html;

                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    container.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Error loading data: Invalid JSON format
                        </div>
                    `;
                }
            });
        });
    </script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


@endsection
