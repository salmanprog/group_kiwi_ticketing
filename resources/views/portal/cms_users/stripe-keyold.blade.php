@extends('portal.master')

@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">

                {{-- Flash Message --}}
                @include('portal.flash-message')

                {{-- Profile Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Stripe Configuration</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            @csrf

                            {{-- Stripe Mode Selection --}}
                                <div class="form-group">
                                    <label class="font-weight-bold">Stripe Mode</label>
                                    <div class="d-flex gap-4 ">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stripe_key_status" id="mode_test" value="test"
                                                {{ currentUser()->stripe_key_status === 'test' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mode_test">Test Mode</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stripe_key_status" id="mode_live" value="live"
                                                {{ currentUser()->stripe_key_status === 'live' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mode_live">Live Mode</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Choose the mode you want to operate in.</small>
                                </div>

                            <hr>

                            {{-- Test Keys --}}
                            <h6 class="text-secondary mt-4"><i class="fa fa-vial mr-1"></i> Stripe Test Keys</h6>
                            <div class="form-group">
                                <label>Test Publishable Key</label>
                                <input type="text" name="test_publishable_key" value="{{ currentUser()->test_publishable_key }}" class="form-control" placeholder="pk_test_************">
                            </div>

                            <div class="form-group">
                                <label>Test Secret Key</label>
                                <input type="text" name="test_secret_key" value="{{ currentUser()->test_secret_key }}" class="form-control" placeholder="sk_test_************">
                            </div>

                            <hr>

                            {{-- Live Keys --}}
                            <h6 class="text-secondary mt-4"><i class="fa fa-bolt mr-1"></i> Stripe Live Keys</h6>
                            <div class="form-group">
                                <label>Live Publishable Key</label>
                                <input type="text" name="live_publishable_key" value="{{ currentUser()->live_publishable_key }}" class="form-control" placeholder="pk_live_************">
                            </div>

                            <div class="form-group">
                                <label>Live Secret Key</label>
                                <input type="text" name="live_secret_key" value="{{ currentUser()->live_secret_key }}" class="form-control" placeholder="sk_live_************">
                            </div>

                            {{-- Submit --}}
                            <div class="form-group mt-4 text-right">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fa fa-save mr-1"></i> Save Settings
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
@endsection
