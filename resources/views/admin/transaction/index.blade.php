@extends('admin.layouts.main')
@section('title')
    Transactions
@endsection
@section('content')
    <?php $sessionUser = auth()->user(); ?>

    <!-- Content -->
    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Transactions</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="pjax" href="{{ route('admin/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Transactions</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="row card-header flex-column flex-md-row pb-0">
            <h4 class="align-middle mb-0">Transactions</h4>
        </div>
          <div class="card-datatable table-responsive">
            <table class="table border-top" id="data-table">
                <thead>
                     <tr>
                        <th>#</th>
                        <th>UserName</th>
                        <th>Transaction Id</th>
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
                    url: '{{ route('admin/transaction/list') }}',
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
                        data: "stripe_transaction_id",
                        responsivePriority: 4
                    },
                    {
                        data: "status",
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
