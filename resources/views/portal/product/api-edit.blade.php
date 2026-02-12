@extends('portal.master')

@section('content')
    @push('stylesheets')
        <link href="{{ asset('admin/assets/scss/products.css') }}" rel="stylesheet" type="text/css">
    @endpush
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')

                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <h3>Edit Ticket</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('product.api.update', $ticket['id'] ?? 0) }}">
                            @csrf

                            <div class="form-section">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Ticket Name</label>
                                        <input type="text" class="form-control" name="ticketName"
                                            value="{{ old('ticketName', $ticket['ticketName'] ?? '') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Ticket Type</label>
                                        <input type="text" class="form-control" name="ticketType"
                                            value="{{ $ticket['ticketType'] ?? '' }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Sale Channel</label>
                                        <input type="text" class="form-control" name="saleChannel"
                                            value="{{ $ticket['saleChannel'] ?? '' }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" name="ticketPrice" class="form-control"
                                            value="{{ old('ticketPrice', $ticket['ticketPrice'] ?? '') }}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" id="description" class="form-control" style="height: 120px;"
                                                placeholder="Enter description here...">{{ old('description', $get_product->description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions mt-4">
                                <button type="submit" class="btn btn-primary">Update API Ticket</button>
                                <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        @include('portal.footer')
    </section>
@endsection

