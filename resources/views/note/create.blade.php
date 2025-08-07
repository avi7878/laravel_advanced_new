{{-- @extends('layouts.main')
@section('title')
Note Create
@endsection
@section('content') --}}
{{-- <div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Note Create</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="home" class="router">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="note" class="noroute">Notes</a>
            </li>
            <li class="breadcrumb-item active">Note Create</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box">
    <div class="card-header justify-content-between">
        <h4 class="align-middle d-sm-inline-block d-none">Note Create</h4>
    </div>
    <div class="card-body">
        {{ view('note/_form')}}
    </div>
</div> --}}
<div class="modal-header">
    <h4 class="modal-title">Note Create</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="col-md-12">
        {{ view('note/_form')}}
</div>
<div class="modal-footer justify-content-between">
   
</div>
