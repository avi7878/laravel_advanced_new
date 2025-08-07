@extends('admin.layouts.main')
@section('title')
Blog Create
@endsection
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Blog</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" class="router pjax">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/blog/index" class="router pjax">Blog</a>
            </li>
            <li class="breadcrumb-item active">Blog Create</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box">
    <div class="card-header justify-content-between">
        <h4 class="align-middle mb-0">Blog Create</h4>
    </div>
    <div class="card-body">
        {{ view('admin/blog/_form',compact('bcategory')) }}
    </div>
</div>

@endsection
