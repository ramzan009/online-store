@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('success') }}
    </div>
@endif

@if (session('info'))
    <div class="alert alert-danger">
        {{ session('info') }}
    </div>
@endif
