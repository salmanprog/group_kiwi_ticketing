@extends('portal.master')

@section('content')
<section class="main-content">
    <div class="row">
        <div class="col-sm-12">

            {{-- Flash Message --}}
            @include('portal.flash-message')

            <div class="card">
                <div class="card-header">
                    <h3>Stripe Configuration</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        @csrf

                        {{-- Stripe Mode --}}
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
                                                {{ ($stripe?->stripe_key_status ?? '') === 'test' ? 'checked' : '' }}>
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
                                                {{ ($stripe?->stripe_key_status ?? '') === 'live' ? 'checked' : '' }}>
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
                                            value="{{ $stripe?->test_publishable_key ?? '' }}"
                                            class="form-control"
                                            placeholder="pk_test_************">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Test Secret Key</label>
                                        <input type="text" name="test_secret_key"
                                            value="{{ $stripe?->test_secret_key ?? '' }}"
                                            class="form-control"
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
                                            value="{{ $stripe?->live_publishable_key ?? '' }}"
                                            class="form-control"
                                            placeholder="pk_live_************">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Live Secret Key</label>
                                        <input type="text" name="live_secret_key"
                                            value="{{ $stripe?->live_secret_key ?? '' }}"
                                            class="form-control"
                                            placeholder="sk_live_************">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
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

    @include('portal.footer')
</section>

<style>
.main-content {
    background: #f8faf9;
    min-height: 100vh;
    padding: 30px;
    padding-top: 90px;
}

.form-check-input {
    margin-top: 7px;
    margin-left: 0rem;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(160, 194, 66, 0.1);
    margin-bottom: 30px;
}

.card-header {
    padding: 20px 30px;
    border-bottom: 1px solid #e5e7eb;
}

.card-body {
    padding: 30px;
}

.form-section {
    border: 1px solid #eaeaea;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 20px;
}

.section-header {
    margin-bottom: 20px;
    border-bottom: 1px solid #e0e6e3;
}

.form-control:focus {
    border-color: #A0C242 !important;
    box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
}

.mode-option {
    padding: 15px;
    border: 1px solid #dce4e0;
    border-radius: 6px;
    cursor: pointer;
}

.mode-option.active {
    border-color: #A0C242;
    background: rgba(160, 194, 66, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #A0C242, #8AB933);
    color: #fff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modeOptions = document.querySelectorAll('.mode-option');

    modeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radioInput = this.querySelector('input[type="radio"]');
            radioInput.checked = true;

            modeOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
        });

        const radioInput = option.querySelector('input[type="radio"]');
        if (radioInput.checked) {
            option.classList.add('active');
        }
    });
});
</script>
@endsection