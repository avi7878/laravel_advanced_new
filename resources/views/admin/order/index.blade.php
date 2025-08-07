@extends('admin.layouts.main')
@section('title')
    Orders
@endsection
@section('content')
    <?php $sessionUser = auth()->user(); ?>

    <!-- Content -->
    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Orders</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="pjax" href="{{ route('admin/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="row card-header flex-column flex-md-row pb-0">
            <h4 class="align-middle mb-0">Orders</h4>
        </div>
         <div class="card-datatable table-responsive">
            <table class="table border-top" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>UserName</th>
                        <th>Product Name</th>
                        <th>transaction Id</th>
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
                    url: '{{ route('admin/order/list') }}',
                    method: 'post',
                }),
                columns: [{
                        data: "id",
                        responsivePriority: 4
                    },
                    {
                        data: "user_name"
                    },
                    {
                        data: "product_title",
                        responsivePriority: 4
                    },
                    {
                        data: "transaction_id",
                        responsivePriority: 2
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
