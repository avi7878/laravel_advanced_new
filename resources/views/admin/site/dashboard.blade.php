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
        <a href="admin/dashboard">Dashboard</a>
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

{{-- <div class=" col-12 mb-3">
  <div class="card h-100">
    <div class="card-header">
      <div class="d-flex justify-content-between mb-3">
        <h5 class="card-title mb-0">Statistics</h5>
      </div>
    </div>
    <div class="card-body">
      <div class="row gy-3">
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center">
            <div class="badge rounded-pill bg-label-primary me-3 p-2">
              <i class="icon-base bx bxs-pie-chart icon-md "></i>
            </div>
            <div class="card-info">
              <h5 class="mb-0">{{ $totalUser }}</h5>
              <small>User</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center">
            <div class="badge rounded-pill bg-label-info me-3 p-2">
              <i class="icon-base bx bx-user icon-md"></i>
            </div>
            <div class="card-info">
              <h5 class="mb-0">{{ $activeUser }}</h5>
              <small>Active User</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center">
            <div class="badge rounded-pill bg-label-danger me-3 p-2">
              <i class="icon-base bx bx-cart icon-md"></i>
            </div>
            <div class="card-info">
              <h5 class="mb-0">{{ $deactiveUser }}</h5>
              <small>Inactive User</small>
            </div>
          </div>
        </div>
       
      </div>
    </div>
  </div>
</div> --}}
@endif
{{view('admin/site/userchart',['activeUser'=>$activeUser,'deactiveUser'=>$deactiveUser])}}
@endsection