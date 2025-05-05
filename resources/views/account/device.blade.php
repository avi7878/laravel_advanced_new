@extends('layouts.main')
@section('title')
Device
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    {{ view('account/component/account_block',compact('model')) }}
    {{-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Device /</span> List</h4> --}}
    <!-- Invoice List Table -->
    <div class="card">
      <div class="card-body">
        <h5 class="mb-1"><span class="text-muted fw-light">Device /</span> List</h5>
      </div>
        <div class="card-datatable table-responsive">
          <table class="datatable-list-table table border-top" id="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Client</th>
                <th>Ip</th>
                <th>Location</th>
                <th>Last Activity</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  app.addCSS([
    'theme/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
    'theme/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css'
  ])
  app.addJS(['theme/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js']);
  documentReady(function() {
    datatableObj = $('#data-table').DataTable({
      ajax: dataTableAjax({
        url: '{{route("account/device-list")}}',
        method: 'post',
      }),
      columns: [{
          data: "id",
          responsivePriority: 4
        },
        {
          data: "client",
          responsivePriority: 2,
        },
        {
          data: "ip",
          responsivePriority: 2
        },
        {
          data: "location",
          responsivePriority: 2,
          bSortable: false,
        },
        {
          data: "last_activity",
          responsivePriority: 2
        },
        {
          data: "action",
          responsivePriority: 2,
          bSortable: false,
        },
      ],
      responsive: true,
      serverSide: true,
      retrieve: true,
      "order": [
        [5, "desc"]
      ]
    });
  });
</script>
@endpush
