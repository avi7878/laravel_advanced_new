@extends('admin.layouts.main')
@section('title')
    Plans
@endsection
@section('content')
    <?php $sessionUser = auth()->user(); ?>

    <!-- Content -->
    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Plans</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin/dashboard') }}" class="pjax">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Plans</li>
            </ol>
        </nav>
    </div>

    <!-- Invoice List Table -->
    <div class="card">
        <div class="row card-header flex-column flex-sm-row pb-0">
            <div class="d-flex justify-content-between align-items-center dt-layout-start col-sm-auto me-auto mt-0">
                <h4 class="card-title mb-0 text-md-start text-center">Plans</h4>
            </div>
            <!-- <h4 class="align-middle "></h4> -->
             <div class="d-flex justify-content-between align-items-center dt-layout-end col-auto ms-auto mt-0">
                <div class="dt-buttons btn-group flex-wrap mb-0">
                    @if ($sessionUser->hasPermission('admin/plan/create'))
                        <a href="admin/plan/create" class="btn btn-primary d-sm-inline-block pjax">Create</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-datatable mx-2">
            <table class="table table-bordered table-responsive col-md-2" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Duration </th>
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
                    url: '{{ route('admin/plan/list') }}',
                    method: 'post',
                }),
                columns: [{
                        data: "id",
                        responsivePriority: 4
                    },
                    {
                        data: "title",
                        responsivePriority: 4
                    }, 
                    {
                        data: "description",
                        responsivePriority: 2
                    },
                    {
                        data: "amount",
                        responsivePriority: 3
                    },
                    {
                        data: "duration",
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