{{-- @extends('portal.master')

@section('content')
<section class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            @include('portal.flash-message')

            <div class="card">
                
              
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Add Media</h3>
                    <a href="{{ route('media.index') }}" class="btn btn-primary">
                        Back to List
                    </a>
                </div>

              
                <div class="card-body">
                    <form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data">
                        @csrf

                     
                        <div class="form-section">
                            <h5 class="section-title">File Name</h5>

                            <div class="form-group">
                                <label>File Name <span class="text-danger">*</span></label>
                                <input type="text" name="filename" class="form-control"
                                       value="{{ old('filename') }}" required>
                            </div>
                        </div>

                      
                        <div class="form-section">
                            <h5 class="section-title">Upload File</h5>

                            <div class="form-group">
                                <label>File <span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                        </div>

                    
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
@endsection --}}



@extends('portal.master')

@section('content')
    @push('stylesheets')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    <style>
        /* --- Matching UI Styling from Create Page --- */
        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
        }

        .custfor-flex-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(160, 194, 66, 0.1);
            margin-bottom: 30px;
            background: #fff;
            overflow: hidden;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 30px;
            color: #1f2937;
        }

        .header-content h3 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }

        .header-actions .btn-outline {
            background: #9FC23F !important;
            border: 1px solid #fff !important;
            border-radius: 8px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-1px);
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.2s ease;
        }

        .form-section:hover {
            transform: translateY(-1px);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e6e3;
        }

        .section-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
        }

        .section-badge {
            background: #f3f4f6;
            color: #374151;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 1px solid #dce4e0;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #A0C242 !important;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            outline: none;
        }

        .form-control[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
        }

        /* File input styling */
        input[type="file"].form-control {
            padding: 10px 12px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .custfor-flex-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .card-body {
                padding: 20px;
            }

            .form-section {
                padding: 20px;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }
        }
    </style>

    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')

                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Add Media</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('media.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- File Name Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>File Name</h5>
                                    <span class="section-badge">Required</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        File Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="filename" class="form-control"
                                           value="{{ old('filename') }}" required>
                                </div>
                            </div>

                            <!-- File Upload Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Upload File</h5>
                                    <span class="section-badge">Required</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        File <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                            </div>

                            <!-- Actions -->
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
    </section>
@endsection