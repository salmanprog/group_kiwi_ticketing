@extends('portal.master')

@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">

                {{-- Flash Message --}}
                @include('portal.flash-message')

                {{-- Profile Card --}}
                <div class="card">
                    <div class="card-header">
                        <h3>Stripe Configuration</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            @csrf

                            {{-- Stripe Mode Selection --}}
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Stripe Mode</h5>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="mode-option">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="radio" name="stripe_key_status"
                                                    id="mode_test" value="test"
                                                    {{ currentUser()->stripe_key_status === 'test' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="mode_test">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge">Test Mode</span>
                                                        <small>For testing purposes</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="mode-option">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="radio" name="stripe_key_status"
                                                    id="mode_live" value="live"
                                                    {{ currentUser()->stripe_key_status === 'live' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="mode_live">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge">Live Mode</span>
                                                        <small>For real transactions</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">
                                    Choose the mode you want to operate in.
                                </div>
                            </div>

                            {{-- Test Keys --}}
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Stripe Test Keys</h5>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Test Publishable Key</label>
                                            <input type="text" name="test_publishable_key"
                                                value="{{ currentUser()->test_publishable_key }}" class="form-control"
                                                placeholder="pk_test_************">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Test Secret Key</label>
                                            <input type="text" name="test_secret_key"
                                                value="{{ currentUser()->test_secret_key }}" class="form-control"
                                                placeholder="sk_test_************">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Live Keys --}}
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Stripe Live Keys</h5>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Live Publishable Key</label>
                                            <input type="text" name="live_publishable_key"
                                                value="{{ currentUser()->live_publishable_key }}" class="form-control"
                                                placeholder="pk_live_************">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Live Secret Key</label>
                                            <input type="text" name="live_secret_key"
                                                value="{{ currentUser()->live_secret_key }}" class="form-control"
                                                placeholder="sk_live_************">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        @include('portal.footer')
    </section>

    <style>
        /* --- Same UI as Organization Type Page --- */
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

        .form-section {
            background: #ffffff;
            border: 1px solid #eaeaea;
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
            border-bottom: 1px solid #e0e6e3;
        }

        .section-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .required {
            color: #e74c3c;
            margin-left: 4px;
        }

        .form-control {
            border: 1px solid #dce4e0;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #A0C242 !important;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            outline: none;
        }

        .form-text {
            color: #7f8c8d;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* Radio Button Styles */
        .mode-option {
            padding: 15px;
            border: 1px solid #dce4e0;
            border-radius: 6px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .mode-option:hover {
            border-color: #A0C242;
            background: rgba(160, 194, 66, 0.05);
        }

        .mode-option.active {
            border-color: #A0C242;
            background: rgba(160, 194, 66, 0.1);
        }

        .form-check-input {
            margin-left: 0 !important;
            margin-right: 10px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #A0C242;
            border-color: #A0C242;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
        }

        .form-check-label {
            cursor: pointer;
            width: 100%;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 10px;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        /* Button Styles */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #eaeaea;
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

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
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
                justify-content: center;
            }
        }
    </style>
    <script>
        // Add click functionality to mode options
        document.addEventListener('DOMContentLoaded', function() {
            const modeOptions = document.querySelectorAll('.mode-option');

            modeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radioInput = this.querySelector('input[type="radio"]');
                    radioInput.checked = true;

                    // Remove active class from all options
                    modeOptions.forEach(opt => opt.classList.remove('active'));

                    // Add active class to clicked option
                    this.classList.add('active');

                    // Trigger change event
                    radioInput.dispatchEvent(new Event('change'));
                });

                // Set initial active state
                const radioInput = option.querySelector('input[type="radio"]');
                if (radioInput.checked) {
                    option.classList.add('active');
                }
            });
        });
    </script>
@endsection
