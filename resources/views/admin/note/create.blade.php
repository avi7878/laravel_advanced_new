@extends('admin.layouts.main')
@section('title')
Note Create
@endsection
@section('content')

<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Note Create</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="home" class="router pjax">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/notes" class="noroute pjax">Notes</a>
            </li>
            <li class="breadcrumb-item active">Note Create</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box">
    <div class="card-header justify-content-between">
        <h4 class="align-middle mb-0">Note Create</h4>
    </div>
    <div class="card-body">
        {{ view('admin/note/_form')}}
    </div>
</div>

@endsection