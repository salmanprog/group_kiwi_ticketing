@extends('portal.master')

@section('content')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            font-size: 14px;
        }

        .invoice-wrapper {
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }

        .invoice-header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
        }

        .invoice-meta {
            font-size: 16px;
            margin-top: 5px;
            color: #666;
        }

        .status.rejected {
            background-color: #4b71fa;
            color: #fff;
            border: 1px solid #4b71fa;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .status.draft {
            background-color: #7bcb4d;
            color: #fff;
            border: 1px solid #7bcb4d;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .status.sent {
            background-color: #36a3f7;
            color: #fff;
            border: 1px solid #36a3f7;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .status.approved {
            background-color: #1fb1ebff;
            color: #fff;
            border: 1px solid #1fb1ebff;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .address-section {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .address-box {
            flex: 1;
            min-width: 260px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        .address-box h4 {
            margin-bottom: 10px;
            color: #007bff;
        }

        .form-section {
            margin-top: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
        }

        .print-value {
            display: none;
            font-size: 15px;
            padding: 8px;
            background: #f4f6f8;
            border-radius: 4px;
            color: #333;
            font-weight: 600;
            margin-top: 4px;
            border: 1px solid #ddd;
        }

        table.product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .product-table th {
            background-color: #f5f5f5;
        }

        .btn-danger,
        .btn-success {
            padding: 5px 10px;
            font-size: 13px;
        }

        @media print {

            input,
            select,
            textarea,
            .btn,
            .select2,
            label,
            form button {
                display: none !important;
            }

            .print-value {
                display: block !important;
            }

            .invoice-wrapper,
            .address-box,
            table,
            th,
            td {
                border: 1px solid #000 !important;
                background-color: #fff !important;
            }

            th {
                background-color: #eee !important;
                -webkit-print-color-adjust: exact;
            }

            .main-content {
                padding: 0;
                margin: 0;
            }

            @page {
                margin: 20mm;
            }
        }
    </style>

    <section class="main-content">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                @include('portal.flash-message')

                <div class="invoice-wrapper">
                    {{-- Header --}}
                    <div class="invoice-header">
                        <div class="invoice-title">Estimate</div>
                        <br>

                        <div class="invoice-meta">
                            <strong>#{{ ucfirst($record->slug) }}</strong> —
                            @switch($record->status)
                                @case('draft')
                                    <span class="status draft">Draft</span>
                                @break

                                @case('sent')
                                    <span class="status sent">Sent</span>
                                @break

                                @case('approved')
                                    <span class="status approved">Approved</span>
                                @break

                                @case('rejected')
                                    <span class="status rejected">Rejected</span>
                                @break

                                @case('revised')
                                    <span class="status draft">Revised</span>
                                @break
                            @endswitch
                        </div>
                        <br>

                        {{-- Address Section --}}
                        <div class="address-section">
                            <div class="address-box">
                                <h4>From</h4>
                                <p>
                                    <strong>{{ $record->company->name }}</strong><br>
                                    <strong>Mobile No:</strong> {{ $record->company->mobile_no }}
                                    <br>
                                    <strong>Email:</strong> {{ $record->company->email }}
                                </p>
                            </div>
                            <div class="address-box">
                                <h4>Invoice To</h4>
                                <p>
                                    <strong>{{ $record->organization->name }}</strong><br>
                                    {{ $record->organization->address_one }}
                                    <br>
                                    <strong>Email:</strong> {{ $record->organization->email }}
                                    <br>
                                    <strong>Phone:</strong> {{ $record->organization->phone }}

                                </p>
                            </div>
                        </div>

                        {{-- Form Start --}}
                        <div class="form-section">
                            <form method="POST" action="{{ route('estimate.update', ['estimate' => $record->slug]) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    @if ($record->contract_id == null)
                                        <div class="form-group">
                                            <label for="client_id">Client</label>
                                            <select name="client_id" id="client_id" class="form-control select2">
                                                <option value="">-- Select Client --</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->client_id }}"
                                                        {{ $record->client_id == $client->client_id ? 'selected' : '' }}>
                                                        {{ $client->first_name }} {{ $client->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="print-value">
                                                <strong>Client:</strong>
                                                {{ optional($clients->firstWhere('id', $record->client_id))->first_name ?? '' }}
                                                {{ optional($clients->firstWhere('id', $record->client_id))->last_name ?? '' }}
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="client_id" value="{{ $record->client_id }}">
                                    @endif

                                    <div class="form-group">
                                        <label for="estimate_date">Estimate Date</label>
                                        <input required type="date" name="estimate_date" class="form-control"
                                            value="{{ $record->issue_date }}">
                                        <div class="print-value">
                                            <strong>Estimate Date:</strong>
                                            {{ \Carbon\Carbon::parse($record->issue_date)->format('F j, Y') }}
                                        </div>
                                    </div>
                                    @if ($record->contract_id == null)
                                        <div class="form-group">
                                            <label for="event_date">Event Date</label>
                                            <input required type="date" name="event_date" class="form-control"
                                                value="{{ $record->event_date }}">
                                            <div class="print-value">
                                                <strong>Expiry Date:</strong>
                                                {{ \Carbon\Carbon::parse($record->event_date)->format('F j, Y') }}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="expiration_date">Expiry Date</label>
                                        <input required type="date" name="expiration_date" class="form-control"
                                            value="{{ $record->valid_until }}">
                                        <div class="print-value">
                                            <strong>Expiry Date:</strong>
                                            {{ \Carbon\Carbon::parse($record->valid_until)->format('F j, Y') }}
                                        </div>
                                    </div>

                                </div>

                                {{-- Product Table --}}
                                <div class="form-row mt-4">
                                    <div class="col-12">
                                        <h5 class="mb-3">Product Details</h5>

                                        <table class="table product-table" id="productTable">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                    <th class="no-print">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Rows will be dynamically added here -->
                                            </tbody>
                                        </table>

                                        <table class="table table-bordered w-50 ml-auto cust-main-table">
                                            <tr>
                                                <th>Subtotal</th>
                                                <td id="subtotalCell">$0.00</td>
                                            </tr>
                                            <tr>
                                                <th>Taxes</th>
                                                <td id="taxCell">$0.00</td>
                                            </tr>
                                            <tr id="discountRow" style="display: none;">
                                                <th>Discount</th>
                                                <td id="discountCell" class="text-danger font-weight-bold">-$0.00</td>
                                            </tr>
                                            <tr>
                                                <th><strong>Total</strong></th>
                                                <td id="totalCell"><strong>$0.00</strong></td>
                                            </tr>
                                        </table>

                                        <!-- Hidden inputs for totals -->
                                        <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                                        <input type="hidden" name="tax_total" id="taxTotalInput" value="0">
                                        <input type="hidden" name="discount_total" id="discountTotalInput" value="0">
                                        <input type="hidden" name="total" id="totalInput" value="0">

                                        <button type="button" class="btn btn-success btn-sm mt-2 no-print"
                                            onclick="addRow()">+
                                            Add Field</button>
                                        <button type="button" class="btn btn-primary btn-sm mt-2 no-print"
                                            data-toggle="modal" data-target="#productModal">+ Add Product</button>
                                    </div>

                                    <button type="button" class="btn btn-info btn-sm mt-2 no-print" data-toggle="modal"
                                        data-target="#taxModal">+ Add Tax</button>
                                    <button type="button" class="btn btn-warning btn-sm mt-2 no-print" data-toggle="modal"
                                        data-target="#discountModal">- Add Discount</button>

                                    {{-- Tax list container --}}
                                    <div id="taxList" class="mt-3">
                                        <!-- Dynamically added taxes with hidden inputs will appear here -->
                                    </div>

                                    {{-- Discount list container --}}
                                    <div id="discountDisplay" class="mt-2">
                                        <!-- Dynamically added discounts with hidden inputs will appear here -->
                                    </div>

                                </div>

                                {{-- Submit --}}
                                @if ($record->status != 'approved')
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                @endif

                                @if ($record->status == 'approved')
                                    <input type="hidden" name="adjust" value="1">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Adjust</button>
                                    </div>
                                @endif

                            </form>
                            @if ($record->status != 'approved')
                                <form method="POST" action="{{ route('estimate.save') }}">
                                    @csrf
                                    <input type="hidden" name="slug" value="{{ $record->slug }}">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-success">Sent</button>
                                    </div>
                                </form>
                            @endif



                        </div>
                    </div>
                </div>

                {{-- Modal --}}
                <div class="modal fade" id="productModal" tabindex="-1" role="dialog"
                    aria-labelledby="productModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Select Products</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>

                            <div class="modal-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td><input type="checkbox" class="product-checkbox"
                                                        data-name="{{ $product->name }}"
                                                        data-price="{{ $product->price }}">
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>${{ number_format($product->price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary btn-sm" onclick="addSelectedProducts()">Add
                                    Selected</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="taxModal" tabindex="-1" role="dialog" aria-labelledby="taxModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="taxForm" onsubmit="event.preventDefault(); addTax();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="taxModalLabel">Add Tax</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="taxName">Tax Name</label>
                                        <input type="text" id="taxName" class="form-control" placeholder="e.g. VAT"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="taxPercent">Tax Percent (%)</label>
                                        <input type="number" id="taxPercent" class="form-control" placeholder="e.g. 10"
                                            min="0" step="0.01" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Add Tax</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="discountModal" tabindex="-1" role="dialog"
                    aria-labelledby="discountModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="discountForm" onsubmit="event.preventDefault(); addDiscount();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="discountModalLabel">Add Discount</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="discountName">Discount Name</label>
                                        <input type="text" id="discountName" class="form-control"
                                            placeholder="e.g. Summer Sale">
                                    </div>
                                    <div class="form-group">
                                        <label for="discountValue">Discount Amount or %</label>
                                        <div class="input-group">
                                            <input type="number" id="discountValue" class="form-control"
                                                placeholder="e.g. 10">
                                            <div class="input-group-append">
                                                <select id="discountType" class="form-control">
                                                    <option value="percent">%</option>
                                                    <option value="fixed">$</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Apply Discount</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
    </section>
    <script>
        const existingEstimateItems = @json($record->items ?? []);
        const existingEstimateTaxes = @json($record->taxes ?? []);
        const existingEstimateDiscounts = @json($record->discounts ?? []); // Uncomment if needed

        let productIndex = 1;
        let taxes = [];
        let discounts = [];

        // Load taxes from server-side (Laravel)
        if (Array.isArray(existingEstimateTaxes)) {
            taxes = existingEstimateTaxes.map(tax => ({
                name: tax.name,
                percent: parseFloat(tax.percent) || 0
            }));
        }


        if (Array.isArray(existingEstimateDiscounts)) {
            discounts = existingEstimateDiscounts.map(discount => ({
                name: discount.name,
                value: parseFloat(discount.value) || 0,
                type: discount.type
            }));
        }


        function addRow() {
            const table = document.querySelector("#productTable tbody");
            const row = document.createElement("tr");

            row.innerHTML = `
            <td><input type="text" name="products[${productIndex}][name]" class="form-control"></td>
            <td><input type="number" name="products[${productIndex}][quantity]" class="form-control" oninput="updateTotal(this)" step="0.01" min="0"></td>
            <td><input type="number" name="products[${productIndex}][price]" class="form-control" oninput="updateTotal(this)" step="0.01" min="0"></td>
            <td class="total-cell">$0.00</td>
            <td class="no-print"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Delete</button></td>
        `;
            table.appendChild(row);
            productIndex++;
        }

        function removeRow(button) {
            const row = button.closest("tr");
            row.remove();
            calculateTotals();
        }

        function updateTotal(input) {
            const row = input.closest("tr");
            const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name*="[price]"]').value) || 0;
            row.querySelector(".total-cell").innerText = `$${(qty * price).toFixed(2)}`;
            calculateTotals();
        }

        function addSelectedProducts() {
            const table = document.querySelector("#productTable tbody");
            const checkboxes = document.querySelectorAll(".product-checkbox:checked");

            checkboxes.forEach((checkbox) => {
                const name = checkbox.dataset.name;
                const price = parseFloat(checkbox.dataset.price).toFixed(2);

                const row = document.createElement("tr");
                row.innerHTML = `
                <td><input type="text" name="products[${productIndex}][name]" class="form-control" value="${name}" readonly></td>
                <td><input type="number" name="products[${productIndex}][quantity]" class="form-control" value="1" oninput="updateTotal(this)" step="0.01" min="0"></td>
                <td><input type="number" name="products[${productIndex}][price]" class="form-control" value="${price}" oninput="updateTotal(this)" step="0.01" min="0"></td>
                <td class="total-cell">$${price}</td>
                <td class="no-print"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Delete</button></td>
            `;
                table.appendChild(row);
                productIndex++;
                checkbox.checked = false;
            });

            $('#productModal').modal('hide');
            calculateTotals();
        }

        function addTax() {
            const name = document.getElementById('taxName').value.trim();
            const percent = parseFloat(document.getElementById('taxPercent').value);

            if (!name || isNaN(percent) || percent < 0) {
                alert('Please enter a valid tax name and percent.');
                return;
            }

            taxes.push({
                name,
                percent
            });
            document.getElementById('taxName').value = '';
            document.getElementById('taxPercent').value = '';
            $('#taxModal').modal('hide');

            renderTaxes();
            calculateTotals();
        }

        function renderTaxes() {
            const container = document.getElementById('taxList');
            container.innerHTML = '';
            taxes.forEach((tax, index) => {
                const div = document.createElement('div');
                div.classList.add('tax-row', 'mb-2');
                div.innerHTML = `
                <strong>${tax.name}:</strong> ${tax.percent}% 
                <button type="button" class="btn btn-danger btn-sm ml-2" onclick="removeTax(${index})">Remove</button>
                <input type="hidden" name="taxes[${index}][name]" value="${tax.name}">
                <input type="hidden" name="taxes[${index}][percent]" value="${tax.percent}">
            `;
                container.appendChild(div);
            });
        }

        function removeTax(index) {
            taxes.splice(index, 1);
            renderTaxes();
            calculateTotals();
        }

        function addDiscount() {
            const name = document.getElementById('discountName').value.trim();
            const value = parseFloat(document.getElementById('discountValue').value);
            const type = document.getElementById('discountType').value;

            if (!name || isNaN(value) || value < 0) {
                alert('Please enter a valid discount name and value.');
                return;
            }

            discounts.push({
                name,
                value,
                type
            });
            document.getElementById('discountName').value = '';
            document.getElementById('discountValue').value = '';
            document.getElementById('discountType').value = 'percent';
            $('#discountModal').modal('hide');

            renderDiscounts();
            calculateTotals();
        }

        function renderDiscounts() {
            const container = document.getElementById('discountDisplay');
            container.innerHTML = '';
            discounts.forEach((discount, index) => {
                const label = discount.type === 'percent' ? `${discount.value}%` : `$${discount.value.toFixed(2)}`;
                const div = document.createElement('div');
                div.classList.add('discount-entry', 'mb-2');
                div.innerHTML = `
                <strong>Discount:</strong> ${discount.name} (${label})
                <button type="button" class="btn btn-danger btn-sm ml-2" onclick="removeDiscount(${index})">Remove</button>
                <input type="hidden" name="discounts[${index}][name]" value="${discount.name}">
                <input type="hidden" name="discounts[${index}][value]" value="${discount.value}">
                <input type="hidden" name="discounts[${index}][type]" value="${discount.type}">
            `;
                container.appendChild(div);
            });
        }

        function removeDiscount(index) {
            discounts.splice(index, 1);
            renderDiscounts();
            calculateTotals();
        }

        function calculateTotals() {
            const rows = document.querySelectorAll("#productTable tbody tr");
            let subtotal = 0;

            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[price]"]').value) || 0;
                subtotal += qty * price;
            });

            let totalTaxAmount = 0;
            taxes.forEach(tax => {
                totalTaxAmount += subtotal * (tax.percent / 100);
            });

            let totalDiscountAmount = 0;
            discounts.forEach(discount => {
                let amount = 0;
                if (discount.type === 'percent') {
                    amount = subtotal * (discount.value / 100);
                } else {
                    amount = discount.value;
                }
                if (amount > subtotal) amount = subtotal;
                totalDiscountAmount += amount;
            });

            if (totalDiscountAmount > subtotal) totalDiscountAmount = subtotal;

            const total = subtotal + totalTaxAmount - totalDiscountAmount;

            document.getElementById("subtotalCell").innerText = `$${subtotal.toFixed(2)}`;

            const taxCell = document.getElementById("taxCell");
            if (taxes.length > 0) {
                const taxDetails = taxes.map(tax => {
                    const amount = subtotal * (tax.percent / 100);
                    return `${tax.name}: $${amount.toFixed(2)}`;
                }).join('<br>');
                taxCell.innerHTML = taxDetails;
            } else {
                taxCell.innerText = '$0.00';
            }

            const discountRow = document.getElementById("discountRow");
            const discountCell = document.getElementById("discountCell");
            if (discounts.length > 0 && totalDiscountAmount > 0) {
                discountRow.style.display = '';
                discountCell.innerHTML =
                    `-$${totalDiscountAmount.toFixed(2)} <small class="text-muted">(${discounts.map(d => d.name).join(', ')})</small>`;
            } else {
                discountRow.style.display = 'none';
                discountCell.innerHTML = '';
            }

            document.getElementById("totalCell").innerHTML = `<strong>$${total.toFixed(2)}</strong>`;
            document.getElementById("subtotalInput").value = subtotal.toFixed(2);
            document.getElementById("taxTotalInput").value = totalTaxAmount.toFixed(2);
            document.getElementById("discountTotalInput").value = totalDiscountAmount.toFixed(2);
            document.getElementById("totalInput").value = total.toFixed(2);
        }

        document.querySelector("#productTable tbody").addEventListener('input', function(e) {
            if (e.target.matches('input')) {
                updateTotal(e.target);
            }
        });

        function loadExistingItems() {
            const table = document.querySelector("#productTable tbody");

            existingEstimateItems.forEach(item => {
                const qty = parseFloat(item.quantity) || 1;
                const price = parseFloat(item.price) || 0;
                const total = qty * price;

                const row = document.createElement("tr");
                row.innerHTML = `
                <td><input type="text" name="products[${productIndex}][name]" class="form-control" value="${item.name}" readonly></td>
                <td><input type="number" name="products[${productIndex}][quantity]" class="form-control" value="${qty}" step="0.01" oninput="updateTotal(this)" min="0"></td>
                <td><input type="number" name="products[${productIndex}][price]" class="form-control" value="${price.toFixed(2)}" step="0.01" oninput="updateTotal(this)" min="0"></td>
                <td class="total-cell">$${total.toFixed(2)}</td>
                <td class="no-print"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Delete</button></td>
            `;
                table.appendChild(row);
                productIndex++;
            });
        }

        window.onload = function() {
            loadExistingItems();
            renderTaxes();
            renderDiscounts();
            calculateTotals();
        };
    </script>

    <style>
        .cust-main-table{
            margin-left: unset !important;
            width: 100% !important;
        }
    </style>
@endsection
