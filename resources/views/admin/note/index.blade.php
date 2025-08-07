@extends('admin.layouts.main')
@section('title')
    Notes
@endsection
@section('content')
    <?php $sessionUser = auth()->user(); ?>

    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Notes</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="admin/dashboard" class="noroute pjax">Dashboard</a>
                </li>
                <li class="breadcrumb-item active pjax">Notes</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="row card-header flex-column flex-sm-row pb-0">
             <div class="d-flex justify-content-between align-items-center dt-layout-start col-sm-auto me-auto mt-0">
                <h4 class="card-title mb-0 text-md-start text-center">Notes</h4>
            </div>
            <!-- <h4 class="align-middle "> Notes</h4> -->
            <div class="d-flex justify-content-between align-items-center dt-layout-end col-auto ms-auto mt-0">
                <div class="dt-buttons btn-group flex-wrap mb-0">
                    @if ($sessionUser->hasPermission('admin/notes/create'))
                        <a href="admin/notes/create" class="btn btn-primary  router pjax "
                            style="float: inline-end;">Create</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class=" table border-top" id="data-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Note</th>
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
        app.addCSS([
            'theme/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
            'theme/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css'
        ])
        app.addJS(['theme/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js']);
        documentReady(function() {
            datatableObj = $('#data-table').DataTable({
                ajax: {
                    url: '{{ route('admin/notes/list') }}',
                    dataSrc: 'data',
                    method: 'post',
                    data: {
                        '_token': CSRF_TOKEN
                    }
                },
                columns: [{
                        data: "id",
                        responsivePriority: 6,
                    },
                    {
                        data: "title",
                        responsivePriority: 6
                    },
                    {
                        data: "description",
                        responsivePriority: 4
                    },
                    {
                        data: "created_at",
                        bSortable: false,
                        responsivePriority: 4
                    },
                    {
                        data: "action",
                        bSortable: false,
                        responsivePriority: 2
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
