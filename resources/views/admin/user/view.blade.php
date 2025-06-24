@extends('admin.layouts.main')
@section('title')
User View
@endsection
@section('content')
<style>
  .btn-label-danger{
    display: unset !important;
  }
</style>
<div class="breadcrumb-box">
  <h4 class="fw-bold py-3 mb-4">User</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="admin/dashboard" class="pjax">Dashboard</a>
      </li>
      <li class="breadcrumb-item">
        <a href="admin/user" class="pjax">User</a>
      </li>
      <li class="breadcrumb-item active">User View</li>
    </ol>
  </nav>
</div>

<!-- Content -->
<div class="row">
  <!-- User Sidebar -->
  <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
    <!-- User Card -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="user-avatar-section">
          <div class="d-flex align-items-center flex-column">
            <img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{ $general->getFileUrl($model->image,'profile') }} "height="100" width="100" alt="User avatar" />
          <div class="user-info text-center">
              <h4 class="mb-2">{{  $model->first_name.' '.$model->last_name }}</h4>
              <span class="badge bg-label-secondary mt-1">Author</span>
            </div>
          </div>
        </div>
        <small class="card-text text-uppercase text-body-secondary small">Details</small>
       
        <div class="info-container">
          <ul class="list-unstyled my-3 py-1">
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-user"></i>
              <span class="fw-medium mx-2">Username:</span>
              <span>{{ $model->first_name.' '.$model->last_name }}</span>
            </li>
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-envelope"></i>
              <span class="fw-medium mx-2">Email:</span>
              <span>{{ $model->email }}</span>
            </li>
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-phone"></i>
              <span class="fw-medium mx-2">Phone Number:</span>
              <span>{{ $model->phone }}</span>
            </li>
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-check"></i>
              <span class="fw-medium mx-2">Status:</span>
              @if($model->status == 0)
              <span class="badge rounded-pill bg-label-danger">Inactive</span>
              @else($model->status == 1)
              <span class="badge rounded-pill bg-label-success">Active</span>
              @endif
            </li>
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-time"></i>
              <span class="fw-medium mx-2">Created at:</span>
              <span>{{ date('Y-m-d h:i A', strtotime($model->created_at)); }}</span>
            </li>
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-time-five"></i>
              <span class="fw-medium mx-2">Update at:</span>
              <span>{{ date('Y-m-d h:i A', strtotime($model->update_at)); }}</span>
            </li>
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-timer"></i>
              <span class="fw-medium mx-2">Time Zone:</span>
              <span>{{ $model->timezone }}</span>
            </li>
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-registered"></i>
              <span class="fw-medium mx-2">Register Ip:</span>
              <span class="text-break">{{ $model->registered_ip }}</span>
            </li>
            <li class="d-flex align-items-center mb-4">
              <i class="icon-base bx bx-flag"></i>
              <span class="fw-medium mx-2">Country:</span>
              <span>{{ $model->country }}</span>
            </li>
          </ul>
          <div class="d-flex justify-content-center">
            <a href="admin/user/update?id={{$_GET['id']}}" class="btn btn-primary me-3 pjax">Edit</a>
            <button onclick="app.confirmAction(this);" data-action="admin/user/delete?id={{$_GET['id']}}" class="btn btn-label-danger">Delete</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /User Card -->
    <!-- Plan Card -->

    <!-- /Plan Card -->
  </div>
  <!--/ User Sidebar -->

  <!-- User Content -->
  <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
    <!--/ User Pills -->

    <!-- Change Password -->
    <div class="card mb-4">
      <h5 class="card-header">Recent Devices</h5>
      <div class="table-responsive">
        <table class="table border-top">
          <thead>
            <tr>
              <th class="text-truncate">Browser</th>
              <th class="text-truncate">Device</th>
              <th class="text-truncate">Location</th>
              <th class="text-truncate">Recent Activities</th>
            </tr>
          </thead>
          <tbody>
            @foreach($deviceData as $device)
            <tr>
              <td class="text-truncate">
                @if($device->type==0)
                <strong>Web</strong>
                @elseif($device->type==1)
                <strong>Android</strong>
                @else
                <strong>IOS</strong>
                @endif
              </td>
              <td class="text-truncate">{{$device->device_uid }}</td>
              <td class="text-truncate">{{$device->ip }}</td>
              <td class="text-truncate">{{ date('Y-m-d h:i A', strtotime($device->created_at)); }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!--/ Change Password -->

    <!-- Two-steps verification -->

    <!--/ Two-steps verification -->

    <!-- Recent Devices -->
    <div class="card mb-4">
      <h5 class="card-header">Activity</h5>
      <div class="table-responsive">
        <table class="table border-top">
          <thead>
            <tr>
              <th class="text-truncate">Browser</th>
              <th class="text-truncate">Device</th>
              <th class="text-truncate">ip</th>
              <th class="text-truncate">Recent Activities</th>
            </tr>
          </thead>
          <tbody>
            @foreach($logData as $log)
            <tr>
              <td class="text-truncate">
                @if($log->type==0)
                <strong>Login Fail</strong>
                @elseif($log->type==1)
                <strong>Login Success</strong>
                @elseif($log->type==2)
                <strong>Login By Remember</strong>
                @elseif($log->type==3)
                <strong>Register</strong>
                @elseif($log->type==4)
                <strong>Login With Otp</strong>
                @elseif($log->type==5)
                <strong>Login With Social Media</strong>
                @else
                <strong>Register With Social Media</strong>
                @endif
              </td>
              <td class="text-truncate">{{$log->client }}</td>
              <td class="text-truncate">{{$log->ip }}</td>
              <td class="text-truncate">{{ date('Y-m-d h:i A', strtotime($log->created_at)); }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Recent Devices -->
  </div>
  <!--/ User Content -->
</div>

<!-- Modals -->


@endsection