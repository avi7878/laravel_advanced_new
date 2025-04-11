@extends('layouts.main')
@section('title')
Log
@endsection
@section('content')
<!-- Content -->
<?= view('account/component/account_block'); ?>
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Log /</span> List</h4>
<!-- Ajax Sourced Server-side -->
<div class="card">
    <div class="card-datatable table-responsive">
        <table class=" table border-top" id="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Device</th>
                    <th>Location </th>
                    <th>Ip Address</th>
                    <th>Type</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!--/ Ajax Sourced Server-side -->
<!-- / Content -->
<div class="content-backdrop fade"></div>
<!--Bootstrap Tables-->
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
            ajax: {
                url: '{{route("account/log-list")}}',
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