@extends('admin.layouts.main')
@section('title')
    Plan Create
@endsection
@section('content')
    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Plan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="admin/dashboard" class="pjax">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="admin/plan" class="pjax">Plans</a>
                </li>
                <li class="breadcrumb-item active">Plan Create</li>
            </ol>
        </nav>
    </div>

    <div class="card card-default color-palette-box">
        <div class="card-header justify-content-between">
            <h4 class="align-middle mb-0">Plan Create</h4>
        </div>
        <div class="card-body">
            <?= view('admin/plan/_form') ?>
        </div>
    </div>
@endsection
