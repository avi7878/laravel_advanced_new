@extends('admin.layouts.main')
@section('title')
Log
@endsection
@section('content')

<!-- Content -->
<?= view('admin/account/component/account_block'); ?>
<!-- Ajax Sourced Server-side -->
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Activity /</span> List</h4>
<!-- Invoice List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatable-list-table table border-top" id="data-table">
      <thead>
        <tr>
          <th>Created At</th>
          <th>Client</th>
          <th>Location</th>
          <th>IP</th>
          <th>Type</th>
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
      ajax: {
        url: '{{route("admin/account/user-activity-list")}}',
        method: 'post',
        dataSrc: 'data',
        data: {
          '_token': CSRF_TOKEN
        },
      },
      columns: [

        {
          data: "created_at",
          responsivePriority: 4
        },
        {
          data: "client",
          responsivePriority: 6
        },
        {
          data: "location",
          responsivePriority: 4
        },
        {
          data: "ip",
          responsivePriority: 4
        },
        {
          data: "type",
          responsivePriority: 4
        },

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