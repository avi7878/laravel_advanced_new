@extends('admin.layouts.main')
@section('title')
Device
@endsection
@section('content')


<!-- Content -->


<div class="breadcrumb-box">
  <h4 class="fw-bold py-3 mb-4">Device</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="admin/dashboard" class="pjax">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Device</li>
    </ol>
  </nav>
</div>

<!-- Invoice List Table -->
<div class="card">
  <div class="card-header justify-content-between pb-0">
    <h4 class="align-middle mb-0">Device </h4>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatable-list-table table border-top" id="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Client</th>
          <th>IP</th>
          <th>Location</th>
          <th>Last Activity</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- / Content -->


<!--Bootstrap Tables-->

@endsection
@push('scripts')
<script>
  documentReady(function() {
    datatableObj = $('#data-table').DataTable({
      ajax: dataTableAjax({
        url: '{{route("admin/device/list")}}',
        method: 'post'
      }),
      columns: [{
          data: "id",
          responsivePriority: 6
        },
        {
          data: "first_name",
          responsivePriority: 2
        },
        {
          data: "email",
          class:'wrap-td',
          responsivePriority: 2
        },
        {
          data: "client",
          responsivePriority: 4,
          sortable: false
        },
        {
          data: "ip",
          class:'wrap-td',
          responsivePriority: 3
        },
        {
          data: "location",
          responsivePriority: 2,
          sortable: false
        },
        {
          data: "last_activity",
          responsivePriority: 1,
          sortable: false
        },
        {
          data: "action",
          responsivePriority: 1,
          sortable: false
        },
      ],
      responsive: true,
      serverSide: true,
      "order": [
        [4, "desc"]
      ]
    });
  });
</script>
@endpush