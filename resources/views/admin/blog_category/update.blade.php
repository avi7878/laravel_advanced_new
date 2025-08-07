@extends('admin.layouts.main')
@section('title')
Blog Category Update
@endsection
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Blog Category</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" class="router pjax">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/blog_category/index" class="router pjax">Blog Category</a>
            </li>
            <li class="breadcrumb-item active">Blog Category Update</li>
        </ol>
    </nav>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="card card-default color-palette-box ">
    <div class="card-header justify-content-between">
        <h4 class="align-middle mb-0">Blog Category Update</h4>
    </div>
      <div class="card-body">
        <?= view('admin/blog_category/_form', compact('blogcat')) ?>
      </div>
    </div>
  </div>
</div>


@endsection