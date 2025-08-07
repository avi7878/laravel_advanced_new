@extends('admin.layouts.main')
@section('title')
    Product Update
@endsection
@section('content')
    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Product</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="admin/dashboard" class="pjax">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="admin/product" class="pjax">Products</a>
                </li>
                <li class="breadcrumb-item active">Products Update</li>
            </ol>
        </nav>
    </div>
    <div class="card card-default color-palette-box">
        <div class="card-header justify-content-between">
            <h4 class="align-middle mb-0">Products Update</h4>
        </div>
        <div class="card-body">
            <?= view('admin/product/_form', compact('model')) ?>
        </div>
    </div>
@endsection
