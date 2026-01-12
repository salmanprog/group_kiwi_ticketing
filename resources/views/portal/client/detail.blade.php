@extends('portal.master')

@section('content')
<style>
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

        body {
            font-family: "Poppins", sans-serif !important;
            background-color: #f5f7fa;
            color: var(--text-color);
        }

        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(160, 194, 66, 0.1);
            margin-bottom: 30px;
            background: #fff;
            overflow: hidden;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 30px;
            color: #1f2937;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }

        .card-body {
            padding: 30px;
        }

        .client-details-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .client-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateX(5px);
            border-color: #A0C242;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
            font-size: 18px;
            flex-shrink: 0;
            font-weight: bold;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 13px;
            color: #7f8c8d;
            font-weight: 500;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
            margin: 0;
        }

        .profile-section {
            text-align: center;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 12px;
        }

        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #A0C242;
            box-shadow: 0 4px 20px rgba(160, 194, 66, 0.2);
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .profile-picture:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(160, 194, 66, 0.3);
        }

        .profile-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 10px 25px;
            font-size: 14px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
        }

        .btn-outline-secondary {
            background: #ffffff;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-outline-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .card-body {
                padding: 20px;
            }

            .client-details-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .profile-section {
                order: -1;
            }

            .profile-picture {
                width: 150px;
                height: 150px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .info-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .info-icon {
                align-self: center;
            }
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }
    </style>
<section class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @include('portal.flash-message')

            <div class="card">
                <div class="card-header custfor-flex-header">
                    <div class="header-content">
                        <h3>View Contact</h3>
                    </div>
                </div>

                <div class="card-body">

                    {{-- ================= Contact Details ================= --}}
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Contact Details</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Title</label>
                                <div class="view-value">{{ $record->title ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Salutation</label>
                                <div class="view-value">{{ $record->salutation ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Account</label>
                                <div class="view-value">
                                    {{ optional($record->organization)->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= Account Information ================= --}}
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Account Information</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Account Name</label>
                                <div class="view-value">{{ $record->organization->name ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact</label>
                                <div class="view-value">{{ $record->organization->contact ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Department</label>
                                <div class="view-value">{{ $record->organization->department ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= Address Details ================= --}}
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Address Details</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <div class="view-value">{{ $record->organization->city ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <div class="view-value">{{ $record->organization->state ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <div class="view-value">{{ $record->organization->country ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Zip</label>
                                <div class="view-value">{{ $record->organization->zip ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Address Line 1</label>
                                <div class="view-value">{{ $record->organization->address_one ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Address Line 2</label>
                                <div class="view-value">{{ $record->organization->address_two ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= Contact Information ================= --}}
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Contact Information</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <div class="view-value">{{ $record->first_name ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <div class="view-value">{{ $record->last_name ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="view-value">{{ $record->email ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <div class="view-value">{{ $record->mobile_no ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fax</label>
                                <div class="view-value">{{ $record->fax ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rep</label>
                                <div class="view-value">{{ $record->rep ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= Contract Detail ================= --}}
                    <div class="form-section">
                        <div class="section-header">
                            <h5>Contract Detail</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Ticket Rate</label>
                                <div class="view-value">{{ $record->ticket_rate ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Catering Menu</label>
                                <div class="view-value">{{ $record->catering_menu ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Catering Price</label>
                                <div class="view-value">{{ $record->catering_price ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Deposit Amount</label>
                                <div class="view-value">{{ $record->deposite_amount ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hours</label>
                                <div class="view-value">{{ $record->hours ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Alt Contacts</label>
                                <div class="view-value">{{ $record->alt_contact ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notes / History</label>
                                <div class="view-value">{{ $record->note_history ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Contract Status</label>
                                <div class="view-value">{{ ucfirst(str_replace('_',' ', $record->contract_status)) }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('portal.footer')
</section>

<style>
.view-value{
    padding:12px 15px;
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:6px;
    font-size:.95rem;
    color:#111827;
}
</style>
@endsection
