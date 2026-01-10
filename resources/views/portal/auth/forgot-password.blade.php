@extends('portal.auth.master')
@section('content')
    <div class="col-5">
        @include('portal.auth.header')
        @include('portal.flash-message')
        <div class="misc-box">
            <form method="post" role="form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="exampleuser1" class="form-label">Email</label>
                    <div class="group-icon">
                        <input id="exampleuser1" type="email" name="email" placeholder="Email" class="form-control"
                            required="">
                        {{-- <span class="icon-user text-muted icon-input"></span> --}}
                    </div>
                </div>
                <div class="clearfix">
                    <div class="">
                        <button type="submit" class="btn btn-login">Submit</button>
                        {{-- <button type="submit" class="btn btn-block btn-primary btn-rounded box-shadow">Submit</button> --}}
                    </div>
                </div>
                <hr>
                <p class="text-center">
                    <a href="{{ route('admin.login') }}">Back To Login</a>
                </p>
            </form>
        </div>
        @include('portal.auth.footer')
    </div>
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
            width: 100%;
            padding: 14px;
            background: #a0c242;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(160, 194, 66, 0.3);
            height: 50px;
            margin-top: 10px;
        }
    </style>
@endsection
