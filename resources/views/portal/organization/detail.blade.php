@extends('portal.master')
@section('content')
<section class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @include('portal.flash-message')

            <div class="card">
                <div class="card-header custfor-flex-header">
                    <div class="header-content">
                        <h3>Account Details</h3>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('organization.edit', ['organization' => $record->slug]) }}" class="btn btn-outline">
                            Edit
                        </a>
                        <a href="{{ route('organization.index') }}" class="btn btn-outline">
                            Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Company INFORMATION -->
                    <!-- <div class="form-section">
                        <div class="section-header">
                            <h5>Company Information</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Company Name</label>
                                <div class="view-field">{{ $company->name ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Company Email</label>
                                <div class="view-field">{{ $company->email ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Company Phone</label>
                                <div class="view-field">{{ $company->mobile_no ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Company Website</label>
                                <div class="view-field">{{ $company->website ?? '-' }}</div>
                            </div>
                        </div>
                    </div> -->

                    <!-- BASIC INFORMATION -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Account Information</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Account Name</label>
                                <div class="view-field">{{ $record->name ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Account Email</label>
                                <div class="view-field">{{ $record->email ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact</label>
                                <div class="view-field">{{ $record->phone ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <div class="view-field">{{ $record->department ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- ADDRESS DETAILS -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Address Information</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Billing City</label>
                                <div class="view-field">{{ $record->city ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Billing State</label>
                                <div class="view-field">{{ $record->state ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Billing Country</label>
                                <div class="view-field">{{ $record->country ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Billing Zip</label>
                                <div class="view-field">{{ $record->zip ?? '-' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Billing Street</label>
                                <div class="view-field">{{ $record->address_one ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Description DETAILS -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Description</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="view-field">{{ $record->description ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

            

                     <!-- System Field DETAILS -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>System Fields</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Created By</label>
                                <div class="view-field">{{ $record->createdBy->name ?? 'N/A' }} {{ TimeWithAgo($record->created_at) ?? '' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Currcency</label>
                                <div class="view-field">USD</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Modified By</label>
                                <div class="view-field">{{ $record->updatedBy->name ?? 'N/A'}} {{ TimeWithAgo($record->updated_at) ?? '' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Exchange Rate</label>
                                <div class="view-field">0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes DETAILS -->
                    <!-- <div class="form-section">
                        <div class="section-header">
                            <h5>Notes</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Add Notes</label>
                                <div class="view-field"><textarea></textarea></div>
                            </div>
                        </div>
                    </div> -->

                    <!-- CONTACT INFORMATION -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Contact Information</h5>
                        </div>

                       <div class="row">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($organization_contacts as $organization_contact)
                                            <tr>
                                                <td>
                                                            @php
                                                                $fullName = trim(($organization_contact->first_name ?? '') . ' ' . ($organization_contact->last_name ?? ''));
                                                            @endphp

                                                            <a href="{{ $fullName !== '' 
                                                                        ? route('client-management.show', ['client_management' => $organization_contact->slug]) 
                                                                        : '#' }}"
                                                            class="btn btn-xs btn-info">
                                                                {{ $fullName !== '' ? $fullName : 'N/A' }}
                                                            </a>
                                                        </td>
                                                <td>{{ $organization_contact->email }}</td>
                                                <td>{{ $organization_contact->mobile_no }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                    </div>

                    <div class="form-section">
                        <div class="section-header">
                            <h5>Notes</h5>
                            <small id="notes-status" class="text-success"></small>
                        </div>

                            <div class="form-section mt-4">
                                @if ($activityLog->count())
                                    <ul class="list-group" id="activityLogList">
                                        @foreach ($activityLog as $log)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong>{{ ucfirst($log->createdBy->name ?? 'Activity') }}</strong>
                                                        <div class="text-muted small">
                                                            {{ $log->notesTextarea ?? '' }}
                                                        </div>
                                                    </div> 
                                                    <small class="text-muted">
                                                        {{ $log->created_at->timezone('America/Los_Angeles')->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <ul class="list-group" id="activityLogList">
                                        <li class="list-group-item">No activity found.</li>
                                    </ul>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Add Notes</label>
                                <input type="hidden" name="client_id" id="client_id" value="{{ $record->client_id }}">
                                <input type="hidden" name="organization_id" id="organization_id"
                                    value="{{ $record->id }}">
                                <textarea id="notesTextarea" class="form-control" rows="4" readonly placeholder="Click here to add notes..."></textarea>
                                <button id="saveNotesBtn" class="btn btn-primary mt-2 d-none">
                                    Save Notes
                                </button>
                            </div>
                    </div>
                    

                    <!-- Estimates INFORMATION -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Estimates Information</h5>
                            <span class="section-badge"><a href="{{ route('estimate.create') }}" target="_blank">Add New Estimate</a></span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Estimate No</th>
                                    <th>Status</th>
                                    <th>Estimate Date</th>
                                    <th>Refrence</th>
                                    <th>Subtotal</th>
                                    <th>Amount</th>
                                    <th>Valid Until</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($record->estimate as $key => $estimate)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><a href="{{ route('estimate.show', ['estimate' => $estimate->slug]) }}" target="_blank">
                                            {{ $estimate->estimate_number ?? '-' }}
                                        </a></td>
                                        <td>{{ $estimate->status ?? '-' }}</td>
                                        <td>{{ $estimate->issue_date ?? '-' }}</td>
                                        <td>-</td>
                                        <td>
                                            {{ isset($estimate->subtotal) 
                                                ? number_format($estimate->subtotal, 2) 
                                                : '-' }}
                                        </td>
                                        <td>
                                            {{ isset($estimate->total) 
                                                ? number_format($estimate->total, 2) 
                                                : '-' }}
                                        </td>
                                        <td>{{ $estimate->valid_until ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            No estimates found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <!-- Contract INFORMATIONs -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Contract Information</h5>
                            <span class="section-badge">
                                <a href="{{ route('estimate.create') }}" target="_blank">Add New Contract</a>
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Contract No</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($record->contract as $key => $contract)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>
                                            <a href="{{ route('contract.show', ['contract' => $contract->slug]) }}" target="_blank">
                                                {{ $contract->contract_number ?? '-' }}
                                            </a>
                                        </td>
                                        <td>{{ $contract->client->name ?? '-' }}</td>
                                        <td>
                                            @if ($contract->is_accept)
                                                <span class="badge bg-success">Accepted</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>

                                        <td>{{ number_format($contract->total ?? 0, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No contract found
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <!-- Invoice INFORMATION -->
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Invoice Information</h5>
                            <span class="section-badge">
                                <a href="{{ route('estimate.create') }}" target="_blank">Add New Invoice</a>
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Status</th>
                                        <th>Invoice Date</th>
                                        <th>Due Date</th>
                                        <th>Reference</th>
                                        <th>Subtotal</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $key => $invoice)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('invoice.show', $invoice->slug) }}" target="_blank">
                                                    {{ $invoice->invoice_number ?? '-' }}
                                                </a>
                                            </td>
                                            <td>{{ $invoice->status ?? '-' }}</td>
                                            <td>{{ $invoice->issue_date ?? '-' }}</td>
                                            <td>{{ $invoice->due_date ?? '-' }}</td>
                                            <td>{{ $invoice->reference ?? '-' }}</td>
                                            <td>{{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                                            <td>{{ number_format($invoice->total ?? 0, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                No invoices found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <!-- EVENT & OPPORTUNITY -->
                    <!-- <div class="form-section">
                        <div class="section-header">
                            <h5>Event & Opportunity</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Organization Type</label>
                                <div class="view-field">
                                    {{ optional($record->organizationType)->name ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Organization Size</label>
                                <div class="view-field">{{ $record->size ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Event History</label>
                                <div class="view-field">
                                    {{ optional($record->eventHistory)->name ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Group Type</label>
                                <div class="view-field">
                                    {{ optional($record->eventType)->name ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Group Size</label>
                                <div class="view-field">{{ $record->group_size ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">First Meeting</label>
                                <div class="view-field">{{ $record->first_meeting ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Event Date</label>
                                <div class="view-field">{{ $record->event_date ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Event Status</label>
                                <div class="view-field">{{ $record->event_status ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Closing Probability</label>
                                <div class="view-field">{{ $record->closing_probability ?? '-' }}%</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Next Objective</label>
                                <div class="view-field">{{ $record->next_objective ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Follow Up Date</label>
                                <div class="view-field">{{ $record->follow_up_date ?? '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <div class="view-field">
                                    @if($record->status == 1)
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Disabled</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
    </div>

    @include('portal.footer')
</section>

    <style>
        /* Clean White Theme */
        .main-content {
            background: #ffffff;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
        }

        .custfor-flex-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background: #ffffff;
            overflow: hidden;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 30px;
            color: #1f2937;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-content h3 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
            color: #1f2937;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-outline {
            background: #9FC23F !important;
            border: 1px solid #fff !important;
            border-radius: 8px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline:hover {
            border: 1px solid #ffffff !important;
            background-color: #8ab02e !important;
            color: white;
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-header h5 {
            margin: 0;
            color: #1f2937;
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
        }

        .section-badge {
            background: #f3f4f6;
            color: #374151;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 0.95rem;
            display: block;
        }

        .required {
            color: #dc2626;
            margin-left: 4px;
        }

        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #ffffff;
            color: #1f2937;
        }

        .form-control:focus {
            border-color: #9fc23f !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-hint {
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* Select2 Customization */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 15px;
            height: auto;
            background: #ffffff;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937;
            font-size: 0.95rem;
            padding: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            color: #fff;
        }

        .btn-secondary {
            background: #ffffff;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .custfor-flex-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-content {
                justify-content: center;
            }

            .card-body {
                padding: 20px;
            }

            .form-section {
                padding: 20px;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }

        /* Input focus animations */
        .form-control:focus {
            transform: translateY(-1px);
        }

        /* Date input styling */
        input[type="date"] {
            color: #1f2937;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.4);
            cursor: pointer;
        }

        /* Placeholder color */
        .form-control::placeholder {
            color: #9ca3af;
        }
    </style>


 <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('notesTextarea');
            const saveBtn = document.getElementById('saveNotesBtn');
            const status = document.getElementById('notes-status');

            const clientId = document.getElementById('client_id').value;
            const orgId = document.getElementById('organization_id').value;

            let originalText = textarea.value;

            // Enable editing on click
            textarea.addEventListener('click', function() {
                textarea.removeAttribute('readonly');
                saveBtn.classList.remove('d-none');
                originalText = textarea.value;
            });

            // Save notes
            saveBtn.addEventListener('click', function() {
                saveBtn.disabled = true;
                status.textContent = 'Saving...';

                fetch("{{ route('account.notes.save') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        credentials: 'same-origin', // ✅ important to send session
                        body: JSON.stringify({
                            notes: textarea.value,
                            client_id: clientId,
                            organization_id: orgId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            status.textContent = 'Error saving';
                            return;
                        }

                        // Clear textarea
                        textarea.value = '';
                        textarea.setAttribute('readonly', true);
                        saveBtn.classList.add('d-none');
                        status.textContent = 'Saved ✔';

                        // Update activity log dynamically
                        const list = document.getElementById('activityLogList');
                        list.innerHTML = '';

                        data.activityLogs.forEach(log => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';

                            li.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>${log.createdBy?.name ?? 'System'}</strong>
                            <div class="text-muted small">
                                ${log.notesTextarea}
                            </div>
                        </div>
                        <small class="text-muted">
                            ${log.created_at}
                        </small>
                    </div>
                `;

                            list.appendChild(li);
                        });

                        setTimeout(() => status.textContent = '', 2000);
                    })
                    .catch(err => {
                        console.error(err);
                        textarea.value = originalText;
                        status.textContent = 'Error saving';
                    })
                    .finally(() => {
                        saveBtn.disabled = false;
                    });
            });
        });
    </script>
@endsection
