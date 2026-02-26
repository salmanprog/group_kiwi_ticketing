@extends('portal.master')

@section('content')

<section class="main-content" style="background:#f8faf9; padding:40px; min-height:100vh;">

    <div class="container">
        <div class="card" style="border:none; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.08);">

            <!-- Header -->
            <div class="card-header" style="background:#ffffff; padding:20px 30px; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-weight:600;">Hold Ticket Details</h3>
            </div>

            <!-- Body -->
            <div class="card-body" style="padding:30px;">

                <!-- Top Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label style="font-weight:600;">Estimate</label>
                        <input type="text" class="form-control" value="{{ $Estimates->slug }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label style="font-weight:600;">Hold Date</label>
                        <input type="date" class="form-control" value="{{ $record->hold_date }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label style="font-weight:600;">Expiry Date</label>
                        <input type="date" class="form-control" value="{{ $record->expiry_date }}" readonly>
                    </div>
                </div>

                <!-- Selected Products Table -->
                <div style="margin-top:40px;">
                    <h5 style="font-weight:600; margin-bottom:20px;">Product Details</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background:#f3f4f6;">
                                <tr>
                                    <th>Product Name</th>
                                    <th width="120">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($record->user_hold_ticket_items->isEmpty())
                                    <tr>
                                        <td colspan="2" class="text-center">No record found</td>
                                    </tr>
                                @else
                                    @foreach($record->user_hold_ticket_items as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

</section>

@endsection