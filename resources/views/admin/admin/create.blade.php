@extends('admin.layouts.main')
@section('title')
Admin Create
@endsection
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Admin</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" class="pjax">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/admin" class="pjax">Admin</a>
            </li>
            <li class="breadcrumb-item active">Admin Create</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box" style="display: flow;">
<div class="card-header justify-content-between">
        <h4 class="align-middle mb-0">Admin Create</h4>
    </div>
  <div class="card-body">
    <?= view('admin/admin/_form',compact('model')) ?>
  </div>
</div>

@endsection