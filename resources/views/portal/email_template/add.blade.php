@extends('portal.master')
@section('content')

<section class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            @include('portal.flash-message')

            <div class="card shadow-sm border-0">

                {{-- HEADER --}}
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">Add Email Template</h4>

                    <div>
                        <button type="button" id="fullscreenBtn" class="btn btn-dark btn-sm me-2">
                            ⛶ Fullscreen
                        </button>

                        <a href="{{ route('email-template.index') }}" class="btn btn-outline-secondary btn-sm">
                            Back
                        </a>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="card-body p-4">

                    <form method="POST"
                          action=""
                          id="templateForm">

                        @csrf
                        @method('PUT')

                        {{-- FORM FIELDS --}}
                        <div class="row g-3 mb-4">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Identifier *</label>
                                <input type="text"
                                       name="identifier"
                                       class="form-control"
                                       value="{{ old('identifier') }}"
                                       required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    To (Receiver Emails)
                                </label>
                                <input type="text"
                                       name="receiver_emails"
                                       class="form-control"
                                       placeholder="example@mail.com, admin@mail.com"
                                       value="{{ old('receiver_emails') }}">
                                <small class="text-muted">
                                    Separate multiple emails with commas
                                </small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="1" >
                                        Active
                                    </option>
                                    <option value="0" >
                                        Disabled
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Email Subject *</label>
                                <input type="text"
                                       name="subject"
                                       class="form-control"
                                       value="{{ old('subject') }}"
                                       required>
                            </div>

                        </div>

                        {{-- EDITOR WRAPPER --}}
                        <div class="editor-wrapper mb-3">

                            {{-- CLOSE BUTTON --}}
                            <button type="button"
                                    id="closeFullscreen"
                                    class="close-fullscreen-btn">
                                ✖
                            </button>

                            <div class="editor-container">

                                <div class="editor-panel">
                                    <div class="panel-header">
                                        HTML Editor
                                    </div>
                                    <div id="html_editor_view"></div>
                                </div>

                                <div class="preview-panel">
                                    <div class="panel-header">
                                        Live Preview
                                    </div>
                                    <iframe id="email-preview"></iframe>
                                </div>

                            </div>
                        </div>

                        <textarea name="content"
                                  id="hidden_content"
                                  style="display:none;"></textarea>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                Add Template
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

/* CARD */
.card {
    border-radius: 12px;
}

/* EDITOR LAYOUT */
.editor-container {
    display: flex;
    gap: 20px;
    height: 70vh;
}

/* PANELS */
.editor-panel,
.preview-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
}

/* PANEL HEADER */
.panel-header {
    padding: 10px 15px;
    background: #f8f9fa;
    font-weight: 600;
    border-bottom: 1px solid #e5e5e5;
}

/* EDITOR */
#html_editor_view {
    flex: 1;
}

/* PREVIEW */
#email-preview {
    flex: 1;
    width: 100%;
    border: none;
    background: #fff;
}

/* FULLSCREEN */
.editor-wrapper {
    position: relative;
}

.editor-wrapper.fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: #fff;
    z-index: 9999;
    padding: 20px;
}

.editor-wrapper.fullscreen .editor-container {
    height: calc(100vh - 40px);
}

/* CLOSE BUTTON */
.close-fullscreen-btn {
    display: none;
    position: absolute;
    top: 15px;
    right: 20px;
    background: #000;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    z-index: 10000;
}

.editor-wrapper.fullscreen .close-fullscreen-btn {
    display: block;
}

/* MOBILE */
@media (max-width: 992px) {
    .editor-container {
        flex-direction: column;
        height: auto;
    }

    .editor-panel,
    .preview-panel {
        height: 300px;
    }
}

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>

<script>

/* ACE INIT */
var editor = ace.edit("html_editor_view");
editor.setTheme("ace/theme/chrome");
editor.session.setMode("ace/mode/html");
editor.setOptions({
    fontSize: "14px",
    wrap: true,
    useWorker: false
});

var initialContent = {!! json_encode(old('content', $record->content ?? '<h1>Hello</h1>')) !!};
editor.setValue(initialContent, -1);

/* LIVE PREVIEW */
function updatePreview() {
    var code = editor.getValue();
    document.getElementById('hidden_content').value = code;

    var previewDoc = document.getElementById('email-preview').contentWindow.document;
    previewDoc.open();
    previewDoc.write(code);
    previewDoc.close();
}

editor.getSession().on('change', updatePreview);
updatePreview();

document.getElementById('templateForm').onsubmit = function () {
    document.getElementById('hidden_content').value = editor.getValue();
};

/* FULLSCREEN */
var wrapper = document.querySelector('.editor-wrapper');
var fullscreenBtn = document.getElementById('fullscreenBtn');
var closeBtn = document.getElementById('closeFullscreen');

function enterFullscreen() {
    wrapper.classList.add('fullscreen');
    editor.resize();
}

function exitFullscreen() {
    wrapper.classList.remove('fullscreen');
    editor.resize();
}

fullscreenBtn.addEventListener('click', enterFullscreen);
closeBtn.addEventListener('click', exitFullscreen);

document.addEventListener('keydown', function(e) {
    if (e.key === "Escape") {
        exitFullscreen();
    }
});

</script>

@endsection