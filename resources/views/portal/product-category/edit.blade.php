{{-- @extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Edit Faq
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('product-category.update',['product_category' => $record->slug]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                              <div class="row">
                                  <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input required type="text" name="name" class="form-control"
                                                    value="{{ $record->name }}">
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
@endsection --}}


@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <i class="fas fa-edit"></i>
                            <h3>Edit Product Category</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('product-category.index') }}" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('product-category.update',['product_category' => $record->slug]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-info-circle"></i>
                                    <h5>FAQ Details</h5>
                                    <span class="section-badge">Edit Mode</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-tag"></i>
                                                Name
                                                <span class="required">*</span>
                                            </label>
                                            <input required type="text" name="name" class="form-control"
                                                value="{{ $record->name }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('portal.footer')
    </section>

    <style>
        /* Same Professional Green Theme */
        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
        }

        .custfor-flex-header{
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
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            border-bottom: none;
            padding: 25px 30px;
            color: white;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-content i {
            font-size: 1.8rem;
            opacity: 0.9;
        }

        .header-content h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            padding: 8px 16px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            background: #f8faf9;
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

        .section-header i {
            width: 35px;
            height: 35px;
            background: #A0C242;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .section-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
        }

        .section-badge {
            background: #eafaf1;
            color: #A0C242;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #d5f5e3;
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

        .form-label i {
            color: #A0C242;
            width: 16px;
        }

        .required {
            color: #e74c3c;
            margin-left: 4px;
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
            border-color: #A0C242;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            outline: none;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
        }

        /* Responsive Design */
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
            
            .header-content {
                justify-content: center;
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
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection
