@extends('admin.layouts.main')
@section('title')
Note Update
@endsection
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Note Update</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" class="router pjax">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/notes" class="router pjax">Notes</a>
            </li>
            <li class="breadcrumb-item active">Note Update</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box">
    <div class="card-header justify-content-between">
        <h4 class="align-middle mb-0">Note Update</h4>
    </div>
    <div class="card-body">
        {{ view('admin/note/_form',['model'=>$model])}}
    </div>
</div>
@endsection