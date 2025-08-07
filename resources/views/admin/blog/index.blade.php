@extends('admin.layouts.main')
@section('title')
Blog
@endsection
@section('content')


<?php $sessionUser = auth()->user();?>
<!-- Content -->

<div class="breadcrumb-box">
  <h4 class="fw-bold py-3 mb-4">Blog</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="admin/dashboard" class="router pjax">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Blog</li>
    </ol>
  </nav>
</div>

<!-- Invoice List Table -->
<div class="card">
  <div class="row card-header flex-column flex-sm-row pb-0">
    <div class="d-flex justify-content-between align-items-center dt-layout-start col-sm-auto me-auto mt-0">
      <h4 class="card-title mb-0 text-md-start text-center">Blog</h4>
    </div>
    <!-- <h4 class="align-middle">Blog</h4> -->
    <div class="d-flex justify-content-between align-items-center dt-layout-end col-auto ms-auto mt-0">
        <div class="dt-buttons btn-group flex-wrap mb-0">
          @if($sessionUser->hasPermission('admin/blog/create'))
          <a href="admin/blog/create" class="btn btn-primary d-sm-inline-block router pjax" style="float: inline-end;">Create</a>
          @endif
        </div>
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatable-list-table table border-top" id="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Category</th>
          <th>Title</th>
          <th>Image</th>
          <th>Created At</th>
          <th>Actions</th>
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
        url: '{{route("admin/blog/list")}}',
        method: 'post',
        dataSrc: 'data',
        data: {
          '_token': CSRF_TOKEN
        },

      },
      columns: [{
          data: "id",
          responsivePriority: 1
        }, //,visible:false
        {
          data: "blog_category_id",
          responsivePriority: 4
        },
        {
          data: "title",
          responsivePriority: 1
        }, //,visible:false
        {
          data: "image",
          responsivePriority: 3
        },
        {
          data: "created_at",
          responsivePriority: 5
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