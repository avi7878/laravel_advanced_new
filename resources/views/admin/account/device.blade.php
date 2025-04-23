@extends('admin.layouts.main')
@section('title')
Device
@endsection
@section('content')


<?= view('admin/account/component/account_block'); ?>
  <!-- Content -->


  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Device /</span> List</h4>

<!-- Invoice List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatable-list-table table border-top" id="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Client</th>
          <th>IP</th>
          <th>Location</th>
          <th>Last Activity</th>
          <th>Action</th>
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
        url: '{{route("admin/account/device-list")}}',
        method: 'post'
      }),
      columns: [{
          data: "id",
          responsivePriority: 4
        },
        {
          data: "client",
          responsivePriority: 2,
          sortable: false
        },
        {
          data: "ip",
          responsivePriority: 2
        },
        {
          data: "location",
          responsivePriority: 2,
          sortable: false
        },
        {
          data: "last_activity",
          responsivePriority: 2
        },
        {
          data: "action",
          responsivePriority: 2,
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