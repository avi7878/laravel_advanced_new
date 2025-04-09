@extends('admin.layouts.main')
@section('title')
Page Update
@endsection
@section('content')

<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Page</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" >Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/pages" class="pjax">Page</a>
            </li>
            <li class="breadcrumb-item active">Page Update</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box">
    <div class="card-header justify-content-between">
        <h4 class="align-middle d-sm-inline-block d-none">Page Update</h4>
    </div>
    <div class="card-body">
        <?= view('admin/page/_form', compact('model')) ?>
    </div>
</div>
@endsection