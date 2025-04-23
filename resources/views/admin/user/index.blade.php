@extends('admin.layouts.main')
@section('title')
Users
@endsection
@section('content')

<?php $sessionUser = auth()->user();?>

<!-- Content -->
<div class="breadcrumb-box">
  <h4 class="fw-bold py-3 mb-4">Users</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ route('admin/dashboard') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Users</li>
    </ol>
  </nav>
</div>


<!-- Invoice List Table -->
<div class="card">
  <div class="card-header justify-content-between">
    <h4 class="align-middle d-sm-inline-block d-none">Users</h4>
    @if($sessionUser->hasPermission('admin/user/create'))
    <a href="admin/user/create" class="btn btn-primary d-sm-inline-block d-none pjax" style="float: inline-end;">Create</a>
    @endif

  </div>
  <div class="card-datatable mx-2">
    <table class="table table-bordered table-responsive" id="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Country</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@endsection
@push('scripts')
<script>

  
documentReady(function() {
    datatableObj = $('#data-table').DataTable({
      ajax: dataTableAjax({
        url: '{{route("admin/user/list")}}',
        method: 'post',
      }),
      columns: [{
          data: "id",
          responsivePriority: 4
        }, //,visible:false
        {
          data: "first_name",
          responsivePriority: 4
        }, //,visible:false
        {
          data: "email",
          responsivePriority: 2
        },
        {
          data: "phone",
          responsivePriority: 3
        },
        {
          data: "country",
          responsivePriority: 4
        },
        {
          data: "status",
          responsivePriority: 5
        },
        {
          data: "created_at",
          responsivePriority: 4
        },
        {
          data: "action",
          bSortable: false,
          responsivePriority: 1
        }
      ],
      responsive: true,
      serverSide: true,
      "order": [
        [0, "desc"]
      ]
    });
});
</script>
@endpush