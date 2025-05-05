@extends('admin.layouts.main')
@section('title')
Email Template Update
@endsection
@section('content')

<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Email</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" >Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/email_template" class="pjax">Email</a>
            </li>
            <li class="breadcrumb-item active">Email Update</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box">
    <div class="card-header justify-content-between">
        <h4 class="align-middle d-sm-inline-block d-none">Email Update</h4>
    </div>
    <div class="card-body">
        <?= view('admin/email_template/_form', compact('model','example')) ?>
    </div>
</div>
@endsection