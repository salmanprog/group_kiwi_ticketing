@extends('portal.auth.master')
@section('content')
    <div class="col-5">
        @include('portal.auth.header')
        @include('portal.flash-message')
        <div class="misc-box">
            <form method="post" role="form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="exampleuser1" class="form-label">New Password</label>
                    <div class="group-icon">
                        <input id="exampleuser1" type="password" name="new_password" placeholder="New Password"
                            class="form-control" required="">
                        <span class="icon-lock text-muted icon-input"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleuser1" class="form-label">Confirm Password</label>
                    <div class="group-icon">
                        <input id="exampleuser1" type="password" name="confirm_password" placeholder="Confirm Password"
                            class="form-control" required="">
                        <span class="icon-lock text-muted icon-input"></span>
                    </div>
                </div>
                <div class="clearfix">
                    <div class="">
                        <button type="submit"
                            class="btn btn-login">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        @include('portal.auth.footer')
    </div>
@endsection

<style>
    .form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        border-radius: 12px !important;
    }

    .btn-login {
        width: 100% !important;
        padding: 14px !important;
        background: #a0c242 !important;
        border: none !important;
        border-radius: 12px !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        color: white !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 15px rgba(160, 194, 66, 0.3) !important;
        height: 50px !important;
        margin-top: 10px !important;
    }
</style>
