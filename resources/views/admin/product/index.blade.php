@extends('admin.layouts.main')

@section('title')
    Products
@endsection

@section('content')
    <?php $sessionUser = auth()->user(); ?>

    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Products</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin/dashboard') }}" class="noroute pjax">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Products</li>
            </ol>
        </nav>
    </div>

    <div class="card ">
        <div class="row card-header flex-column flex-sm-row pb-0 ">
             <div class="d-flex justify-content-between align-items-center dt-layout-start col-sm-auto me-auto mt-0">
                <h4 class="card-title mb-0 text-md-start text-center">Products</h4>
            </div>
            <!-- <h4 class="align-middle"> Products</h4> -->
             <div class="d-flex justify-content-between align-items-center dt-layout-end col-auto ms-auto mt-0">
                <div class="dt-buttons btn-group flex-wrap mb-0">
                    @if ($sessionUser->hasPermission('admin/product/create'))
                        <a href="admin/product/create" class="btn btn-primary d-sm-inline-block pjax"
                            style="float: inline-end;">Create</a>
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
                        <th>Image</th>
                        <th>Description</th>
                        <th>Amount</th>
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
                    url: '{{ route('admin/product/list') }}',
                    method: 'post',
                    dataSrc:'data',
                    data:{
                        '_token':CSRF_TOKEN
                    }
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
                        data: "image",
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
                        data: "status",
                        responsivePriority: 5
                    },
                    {
                        data: "created_at",
                        responsivePriority: 4
                    },
                    {
                        data: "action",
                        orderable: false,
                        responsivePriority: 1
                    }
                ],
                responsive: true,
                serverSide: true,
                order: [
                    [0, "desc"]
                ]
            });
        });
    </script>
@endpush
