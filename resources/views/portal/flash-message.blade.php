{{-- @if ($errors->any())
    <div class="alert bg-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        @foreach ($errors->all() as $error)
            <p><strong>Error!</strong> {{ $error }}</p>
        @endforeach
    </div>
@endif
@if (Session::has('warning'))
<div class="alert bg-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Warning!</strong> {{ Session::get('warning') }}
</div>
@endif
@if (Session::has('error'))
<div class="alert bg-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Error!</strong> {{ Session::get('error') }}
</div>
@endif
@if (Session::has('info'))
<div class="alert bg-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Information!</strong> {{ Session::get('info') }}
</div>
@endif
@if (Session::has('success'))
<div class="alert bg-success alert-dismissible margin-b-0" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Success!</strong> {{ Session::get('success') }}
</div>
@endif --}}


@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        @foreach ($errors->all() as $error)
            <p class="mb-1"><strong>Error!</strong> {{ $error }}</p>
        @endforeach
    </div>
@endif

@if (Session::has('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Warning!</strong> {{ Session::get('warning') }}
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Error!</strong> {{ Session::get('error') }}
    </div>
@endif

@if (Session::has('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Information!</strong> {{ Session::get('info') }}
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show margin-b-0" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Success!</strong> {{ Session::get('success') }}
    </div>
@endif


<style>
    .alert {
        background-color: white;
        border-left: 4px solid;
        border-radius: 0.375rem;
    }

    .alert-danger {
        border-color: #dc3545;
        color: #721c24;
    }

    .alert-warning {
        border-color: #ffc107;
        color: #856404;
    }

    .alert-info {
        border-color: #17a2b8;
        color: #0c5460;
    }

    .alert-success {
        border-color: #9bc03e;
        color: #155724;
    }

    .alert .close {
        color: inherit;
        opacity: 0.8;
    }
</style>
