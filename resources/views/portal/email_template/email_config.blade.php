@extends('portal.master')
@section('content')
<section class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @include('portal.flash-message')
            <div class="card">
                <div class="card-header">
                    <h3>SMTP Configuration</h3>
                </div>
                <div class="card-body smtp-body">
                    <form action="{{ route('user-smtp.createOrupdate') }}" method="POST">
                        @csrf
                        @php $smtp = $smtp ?? null; @endphp

                        <div class="form-section">
                            <div class="form-group row">
                                <label class="col-sm-2">Mail Driver</label>
                                <div class="col-sm-10">
                                    <select name="mail_driver" class="form-control">
                                        <!-- <option value="">None</option> -->
                                        <!-- <option value="sendmail" {{ ($smtp->mail_driver ?? '')=='sendmail' ? 'selected' : '' }}>Sendmail</option> -->
                                        <option value="smtp" {{ ($smtp->mail_driver ?? 'smtp')=='smtp' ? 'selected' : '' }}>SMTP</option>
                                        <!-- <option value="mailgun" {{ ($smtp->mail_driver ?? '')=='mailgun' ? 'selected' : '' }}>Mailgun</option> -->
                                        <!-- <option value="ses" {{ ($smtp->mail_driver ?? '')=='ses' ? 'selected' : '' }}>Amazon SES</option> -->
                                        <!-- <option value="postmark" {{ ($smtp->mail_driver ?? '')=='postmark' ? 'selected' : '' }}>Postmark</option> -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">Mail Host</label>
                                <div class="col-sm-10">
                                    <input type="text" name="mail_host" class="form-control" value="{{ $smtp->mail_host ?? '' }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">Mail Port</label>
                                <div class="col-sm-10">
                                    <input type="text" name="mail_port" class="form-control" value="{{ $smtp->mail_port ?? '587' }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" name="mail_username" class="form-control" value="{{ $smtp->mail_username ?? '' }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">Password</label>
                                <div class="col-sm-10">
                                    <input type="text" name="mail_password" class="form-control" value="{{ $smtp->mail_password ?? '' }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">Encryption</label>
                                <div class="col-sm-10">
                                    <select name="mail_encryption" class="form-control">
                                        <!-- <option value="">None</option>
                                        <option value="ssl" {{ ($smtp->mail_encryption ?? '')=='ssl' ? 'selected' : '' }}>SSL</option> -->
                                        <option value="tls" {{ ($smtp->mail_encryption ?? 'tls')=='tls' ? 'selected' : '' }}>TLS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">From Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="mail_no_replay" class="form-control" value="{{ $smtp->mail_no_replay ?? '' }}" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection