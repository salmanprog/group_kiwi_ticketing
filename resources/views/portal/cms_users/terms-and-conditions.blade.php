@extends('portal.master')

@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">

                {{-- Flash Message --}}
                @include('portal.flash-message')

                {{-- Profile Card --}}
                <div class="card">
                    <div class="card-header">
                        <h3>Terms & Conditions Configuration</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('portal.update-terms-and-conditions') }}" method="POST">
                            @csrf

                            {{-- Form Section --}}
                            <div class="form-section">
                                <div class="section-header">
                                    <h5><i class="fas fa-file-contract me-2"></i> Edit Terms and Conditions</h5>
                                    <span class="badge">Last Updated: {{  ($record) ? $record->updated_at->format('d M, Y') : 'Never' }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Content <span class="required">*</span>
                                    </label>
                                    
                                    {{-- CKEditor Textarea --}}
                                    <div class="editor-container">
                                        <textarea name="content" id="terms-editor" class="form-control">
                                            {{ old('content', $record->content ?? '') }}
                                        </textarea>
                                    </div>
                                    <small class="form-text text-muted">Use the editor above to format your terms, add lists, or links.</small>
                                    
                                    @error('content')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Hidden IDs --}}
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id ?? '' }}">
                            </div>

                            {{-- Form Actions --}}
                            <div class="form-actions">
                                <button type="reset" class="btn btn-light border" style="color: #333;">Reset Changes</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Terms
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        @include('portal.footer')
    </section>

    {{-- Additional Styles for the Editor --}}
    <style>
        /* Ensuring the editor box fits your theme */
        .ck-editor__editable_inline {
            min-height: 300px;
            border-bottom-left-radius: 6px !important;
            border-bottom-right-radius: 6px !important;
        }
        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
            border-color: #dce4e0 !important;
        }
        .ck.ck-toolbar {
            border-color: #dce4e0 !important;
            background: #f8faf9 !important;
            border-top-left-radius: 6px !important;
            border-top-right-radius: 6px !important;
        }
    </style>

    {{-- CKEditor 5 Script --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editorElement = document.querySelector('#terms-editor');
            
            if (editorElement) {
                ClassicEditor
                    .create(editorElement, {
                        toolbar: [
                            'heading', '|', 
                            'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 
                            'blockQuote', 'insertTable', '|', 
                            'undo', 'redo'
                        ]
                    })
                    .then(editor => {
                        console.log('Editor was initialized');
                    })
                    .catch(error => {
                        console.error('Editor error:', error);
                    });
            }

            // --- Your existing mode-option script ---
            const modeOptions = document.querySelectorAll('.mode-option');
            modeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radioInput = this.querySelector('input[type="radio"]');
                    if(radioInput) {
                        radioInput.checked = true;
                        modeOptions.forEach(opt => opt.classList.remove('active'));
                        this.classList.add('active');
                        radioInput.dispatchEvent(new Event('change'));
                    }
                });
            });
        });
    </script>
@endsection