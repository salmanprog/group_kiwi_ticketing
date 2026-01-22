@extends('portal.master')
@section('content')

    <style>
        /* Enhanced Mobile Responsive CSS */
        :root {
            --primary-color: #A0C242;
            --primary-dark: #8AA835;
            --primary-light: #E8F4D3;
            --secondary-color: #2C3E50;
            --light-bg: #F8F9FA;
            --border-color: #E0E0E0;
            --text-color: #333333;
            --text-light: #6C757D;
        }

        .estimate-wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
            border: 1px solid var(--border-color);
            width: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: "Poppins", sans-serif !important;
            background-color: #f5f7fa;
            color: var(--text-color);
            font-size: 14px !important;
            line-height: 1.4;
        }

        .estimate-header {
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .estimate-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .estimate-meta {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            font-size: 16px;
            flex-wrap: wrap;
            flex-direction: column;
        }

        .estimate-number {
            background: var(--primary-light);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            color: var(--secondary-color);
            display: inline-block;
            font-size: 14px;
            text-transform: capitalize;
        }

        .text-muted {
            font-size: 14px;
        }

        .status {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
            margin: 5px 0;
        }

        .status.sent {
            background: #36a3f7;
        }

        .status.approved {
            background: #28a745;
        }

        .status.rejected {
            background: #dc3545;
        }

        .status.revised {
            background: #ffc107;
            color: #212529;
        }

        .status.draft {
            background: var(--primary-color);
        }

        .address-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
        }

        .address-box {
            width: 100%;
            padding: 20px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
        }

        .address-box h4 {
            color: #1f2937;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 8px;
            font-size: 18px;
        }

        table.product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .product-table th {
            background: #F7FAFC;
            color: var(--secondary-color);
            font-weight: 600;
            padding: 12px 10px;
            text-align: left;
            border: none;
            font-size: 14px;
        }

        .product-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        .product-table tr:hover {
            background-color: var(--light-bg);
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: var(--light-bg);
            border-radius: 8px;
            overflow: hidden;
            display: block;
            overflow-x: auto;
        }

        .summary-table th,
        .summary-table td {
            padding: 12px 10px;
            font-size: 14px;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-table th {
            color: var(--secondary-color);
            font-weight: 600;
            text-align: left;
        }

        .summary-table td {
            text-align: right;
            font-weight: 500;
        }

        .summary-table tr:last-child {
            background: var(--primary-color);
            color: white;
            font-weight: 700;
        }

        .summary-table tr:last-child td {
            border-bottom: none;
        }

        .text-right {
            text-align: right;
        }

        .font-weight-bold {
            font-weight: 700;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #ffffff !important;
        }

        .text-muted {
            color: var(--text-light);
        }

        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 12px 20px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            width: 100%;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            color: white;
        }

        .btn-outline-danger {
            background: transparent;
            border: 2px solid #dc3545;
            color: #dc3545;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-1px);
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 30px;
            padding: 20px;
            background: var(--light-bg);
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .notes-section {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            border-left: 4px solid var(--primary-color);
        }

        .notes-section h5 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 18px;
        }

        .edit-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            display: inline-block;
            margin-top: 10px;
        }

        .edit-link:hover {
            color: var(--primary-dark);
        }

        .activity-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            border: 1px solid #E0E0E0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
            box-sizing: border-box;
        }

        .section-header {
            color: #1f2937;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #E8F4D3;
        }

        .activity-table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            display: block;
            overflow-x: auto;
        }

        /* Scrollbar Styling */
        .activity-table-container::-webkit-scrollbar {
            width: 8px;
        }

        .activity-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .activity-table-container::-webkit-scrollbar-thumb {
            background: #A0C242;
            border-radius: 10px;
        }

        .activity-table-container::-webkit-scrollbar-thumb:hover {
            background: #8AA835;
        }

        .activity-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            font-size: 14px;
            min-width: 600px;
        }

        .activity-table th {
            background: #F7FAFC;
            color: #2C3E50;
            font-weight: 600;
            padding: 12px 10px;
            text-align: left;
            border-bottom: 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .activity-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
        }

        .activity-table tr:hover {
            background-color: #f8f9fa;
        }

        .activity-table tr:last-child td {
            border-bottom: none;
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: white;
        }

        .badge-update {
            background: #36a3f7;
        }

        .badge-create {
            background: #28a745;
        }

        .badge-edit {
            background: #A0C242;
        }

        .badge-delete {
            background: #dc3545;
        }

        .badge-view {
            background: #6c757d;
        }

        .theme-bg {
            background-color: #A0C242 !important;
        }

        .theme-text {
            color: #A0C242 !important;
        }

        .theme-border {
            border-color: #A0C242 !important;
        }

        .theme-table thead {
            background-color: #A0C242;
            color: white;
        }

        .theme-table {
            border: 1px solid #A0C242;
        }

        .modal-title {
            color: var(--secondary-color);
            font-weight: 600;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-light), #f0f7e4);
            border-bottom: 2px solid var(--primary-color);
        }

        .modal-header {
            background-color: #A0C242;
            color: white;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-header .btn-close {
            color: #000;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .table-bordered {
            border: 1px solid #A0C242;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .theme-badge {
            background-color: #A0C242;
            color: white;
        }

        /* Tablet and Desktop Styles */
        @media (min-width: 768px) {
            .estimate-wrapper {
                padding: 30px;
            }

            .estimate-title {
                font-size: 18px;
            }

            .estimate-meta {
                flex-direction: row;
                align-items: center;
            }

            .address-section {
                flex-direction: row;
            }

            .address-box {
                flex: 1;
                min-width: 280px;
            }

            .action-buttons {
                flex-direction: row;
            }

            .btn {
                width: auto;
                margin-bottom: 0;
            }

            table.product-table {
                display: table;
                overflow-x: visible;
                white-space: normal;
            }

            .summary-table {
                display: table;
                overflow-x: visible;
            }

            .activity-table {
                min-width: auto;
            }
        }

        /* Large Desktop Styles */
        @media (min-width: 1200px) {
            .activity-section {
                max-width: 1330.5px;
                margin-inline: auto;
            }
        }

        /* Small Mobile Styles */
        @media (max-width: 480px) {
            .estimate-wrapper {
                padding: 15px;
            }

            .estimate-title {
                font-size: 18px;
            }

            .address-box {
                padding: 15px;
            }

            .action-buttons {
                padding: 15px;
            }

            .notes-section {
                padding: 15px;
            }

            .activity-section {
                padding: 15px;
            }

            .activity-table th,
            .activity-table td {
                padding: 8px 6px;
            }

            .badge {
                font-size: 10px;
                padding: 3px 6px;
            }

            .product-table th,
            .product-table td {
                padding: 8px 6px;
                font-size: 12px;
            }

            .summary-table th,
            .summary-table td {
                padding: 10px 8px;
                font-size: 13px;
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

                    {{-- Product Table --}}
                    <div>
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Product Price</th>
                                    <th>Tax</th>
                                    <th>Gratuity</th>
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

                    {{-- Notes and Terms --}}
                    @if ($estimate->note || $estimate->terms)
                        <div class="notes-section">
                            @if ($estimate->note)
                                <h5><i class="fas fa-sticky-note me-2"></i>Note</h5>
                                <p>{{ $estimate->note }}</p>
                            @endif
                            @if ($estimate->terms)
                                <h5><i class="fas fa-file-contract me-2"></i>Terms</h5>
                                <p>{{ $estimate->terms }}</p>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>

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

    </section>
    <script>
        // document.querySelectorAll('.view-details').forEach(button => {
        //     button.addEventListener('click', function() {
        //         const description = this.dataset.description || 'No description';
        //         const allData = this.dataset.alldata || '{}';
        //         document.getElementById('modalDescription').innerText = description;

        //         const container = document.getElementById('estimateData');
        //         container.innerHTML = ''; // Clear previous data

        //         try {
        //             let data = JSON.parse(allData);
        //             if (typeof data === 'string') {
        //                 data = JSON.parse(data); // handle double encoding
        //             }
        //             console.log(data)
        //             const estimate = data.estimate || {};
        //             const items = data.items || [];
        //             const taxes = data.taxes || [];
        //             const discounts = data.discounts || [];

        //             let html = `
    //         <div class="mb-4">
    //             <h5 class="text-primary">Estimate Details</h5>
    //             <table class="table table-bordered table-sm">
    //                 <tbody>
    //                     <tr><th>ID</th><td>${estimate.id ?? '-'}</td></tr>
    //                     <tr><th>Estimate #</th><td>${estimate.estimate_number ?? '-'}</td></tr>
    //                     <tr><th>Status</th><td>${estimate.status ?? '-'}</td></tr>
    //                     <tr><th>Total</th><td>${estimate.total ?? '-'}</td></tr>
    //                     <tr><th>Issue Date</th><td>${estimate.issue_date ?? '-'}</td></tr>
    //                     <tr><th>Valid Until</th><td>${estimate.valid_until ?? '-'}</td></tr>
    //                     <tr><th>Created At</th><td>${estimate.created_at ?? '-'}</td></tr>
    //                 </tbody>
    //             </table>
    //         </div>
    //     `;

        //             if (items.length > 0) {
        //                 html += `
    //             <div class="mb-4">
    //                 <h5 class="text-success">Items</h5>
    //                 <table class="table table-bordered table-sm">
    //                     <thead>
    //                         <tr>
    //                             <th>Name</th>
    //                             <th>Qty</th>
    //                             <th>Unit</th>
    //                             <th>Price</th>
    //                             <th>Total</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //                         ${items.map(item => `
        //                                 <tr>
        //                                     <td>${item.name}</td>
        //                                     <td>${item.quantity}</td>
        //                                     <td>${item.unit}</td>
        //                                     <td>${item.price}</td>
        //                                     <td>${item.total_price}</td>
        //                                 </tr>`).join('')}
    //                     </tbody>
    //                 </table>
    //             </div>
    //         `;
        //             }

        //             if (taxes.length > 0) {
        //                 html += `
    //             <div class="mb-4">
    //                 <h5 class="text-warning">Taxes</h5>
    //                 <table class="table table-bordered table-sm">
    //                     <thead>
    //                         <tr>
    //                             <th>Name</th>
    //                             <th>Percent</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //                         ${taxes.map(tax => `
        //                                 <tr>
        //                                     <td>${tax.name}</td>
        //                                     <td>${tax.percent}%</td>
        //                                 </tr>`).join('')}
    //                     </tbody>
    //                 </table>
    //             </div>
    //         `;
        //             }

        //             if (discounts.length > 0) {
        //                 html += `
    //             <div class="mb-4">
    //                 <h5 class="text-danger">Discounts</h5>
    //                 <table class="table table-bordered table-sm">
    //                     <thead>
    //                         <tr>
    //                             <th>Name</th>
    //                             <th>Value</th>
    //                             <th>Type</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //                         ${discounts.map(discount => `
        //                                 <tr>
        //                                     <td>${discount.name}</td>
        //                                     <td>${discount.value}</td>
        //                                     <td>${discount.type}</td>
        //                                 </tr>`).join('')}
    //                     </tbody>
    //                 </table>
    //             </div>
    //         `;
        //             }

        //             // Fallback if no data
        //             if (!items.length && !taxes.length && !discounts.length) {
        //                 html += `<p class="text-muted">No related data found.</p>`;
        //             }

        //             container.innerHTML = html;

        //         } catch (e) {
        //             container.innerHTML = `<p class="text-danger">Invalid JSON format</p>`;
        //         }
        //     });
        // });
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
