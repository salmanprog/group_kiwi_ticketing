@extends('portal.master')

@section('content')
<section class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            @include('portal.flash-message')

            <div class="card">
                
                {{-- Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Add Media</h3>
                    <a href="{{ route('media.index') }}" class="btn btn-primary">
                        Back to List
                    </a>
                </div>

                {{-- Body --}}
                <div class="card-body">
                    <form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- File Name --}}
                        <div class="form-section">
                            <h5 class="section-title">File Name</h5>

                            <div class="form-group">
                                <label>File Name <span class="text-danger">*</span></label>
                                <input type="text" name="filename" class="form-control"
                                       value="{{ old('filename') }}" required>
                            </div>
                        </div>

                        {{-- File Upload --}}
                        <div class="form-section">
                            <h5 class="section-title">Upload File</h5>

                            <div class="form-group">
                                <label>File <span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>

                            <button type="reset" class="btn btn-secondary">
                                Reset
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    @include('portal.footer')
</section>
@endsection