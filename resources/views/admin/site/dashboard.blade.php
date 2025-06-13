@extends('admin.layouts.main')
@section('title')
Dashboard
@endsection
@section('content')
<?php $sessionUser = auth()->user();?>
<style>
  .more {
    color: rgba(255, 255, 255, .8);
    display: block;
    padding: 3px 0;
    position: relative;
    text-align: center;
    text-decoration: none;
    z-index: 10;
  }
  </style>

<div class="breadcrumb-box">
  <h4 class="fw-bold py-3 mb-4">Dashboard</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="admin/dashboard" class="pjax">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div>

@if($sessionUser->hasPermission('admin_setting'))

<div class="row g-6 mb-6">
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-user icon-lg"></i></span>
          </div>
          <h4 class="mb-0">{{ $totalUser }}</h4>
        </div>
        <p class="mb-2">Total Users</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-success  h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-success "><i class="icon-base bx bx-user-check icon-lg"></i></span>
          </div>
          <h4 class="mb-0">{{ $activeUser }}</h4>
        </div>
        <p class="mb-2">Active Users</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-user-x icon-lg"></i></span>
          </div>
          <h4 class="mb-0">{{ $deactiveUser }}</h4>
        </div>
        <p class="mb-2">Inactive Users</p>
      </div>
    </div>
  </div>
</div>
@endif
{{view('admin/site/userchart',['activeUser'=>$activeUser,'deactiveUser'=>$deactiveUser])}}
@endsection