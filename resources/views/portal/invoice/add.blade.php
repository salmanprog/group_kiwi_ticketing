@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Estimate Details
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('estimate.store') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                        <label>Client</label>
                                        <select name="client_id" class="form-control select2">
                                            <option value="">-- Select Type --</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->client_id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Estimate Date</label>
                                        <input required type="date" name="estimate_date" class="form-control"
                                            value="{{ old('estimate_date') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('portal.footer')
    </section>
@endsection
