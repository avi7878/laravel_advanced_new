@extends('layouts.main')
@section('title')
Support
@endsection
@section('content')

<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Support</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="dashboard" class="noroute">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Support</li>
        </ol>
    </nav>
</div>


<div class="card">
      <div class="row card-header flex-column flex-sm-row pb-0">
        <div class="d-flex justify-content-between align-items-center dt-layout-start col-sm-auto me-auto mt-0">
              <h4 class="align-middle mb-0"> Support</h4>
        </div>
        <div class="d-flex justify-content-between align-items-center dt-layout-end col-auto ms-auto mt-0">
            <div class="dt-buttons btn-group flex-wrap mb-0">
                <a onclick="app.showModalView('support/create')" class="btn btn-primary router text-white pjax" style="float: inline-end;">Create Ticket</a>
            </div>
        </div> 
    </div>
    <!-- <div class="card-header justify-content-between">
        <h4 class="align-middle mb-0"> Support</h4>
        <a onclick="app.showModalView('support/create')" class="btn btn-primary router text-white pjax" style="float: inline-end;">Create Ticket</a>
    </div> -->
    <div class="card-datatable table-responsive">
        <table class=" table border-top data-table" id="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Department</th>
                    <th>Date Created</th>
                    <th>Last Update</th>
                    <th>Action</th>
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
        datatableObj = $('.data-table').DataTable({
            
            ajax: {
                url: 'support/list',
                dataSrc: 'data',
                method: 'post',
                data: {
                    '_token': CSRF_TOKEN    
                }
            },

            columns: [
                {
                    data: "id",
                    responsivePriority: 6,
                },
                {
                    data: "title",
                    responsivePriority: 6
                },
                {
                    data: "body",
                    responsivePriority: 4
                },
                {
                    data: "team_id",
                    responsivePriority: 4
                },
                {
                    data: "created_at",
                    bSortable: false,
                    responsivePriority: 4
                },
                {
                    data: "updated_at",
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
