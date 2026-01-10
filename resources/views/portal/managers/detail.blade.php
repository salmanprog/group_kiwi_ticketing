@extends('portal.master')
@section('content')
<section class="main-content py-4">
    <div class="container">
        @include('portal.flash-message')

          <!-- Manager Details Card -->
        @if($record)
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Manager Details</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p><strong>Name:</strong> {{ $record->name }}</p>
                        <p><strong>Email:</strong> {{ $record->email }}</p>
                        <p><strong>Mobile No:</strong> {{ $record->mobile_no }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <p><strong>Profile Picture:</strong></p>
                        <img src="{{ $record->image_url }}" 
                             alt="Admin Picture" class="img-fluid rounded border" style="max-height: 180px;" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
        @endif
      
    </div>

    @include('portal.footer')
</section>
@endsection
