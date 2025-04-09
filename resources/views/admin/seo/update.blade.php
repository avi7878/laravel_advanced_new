@extends('admin.layouts.main')
@section('title')
Seo Meta Update
@endsection
@section('content')

<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Seo Meta</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" >Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/seo/meta" class="pjax">Seo Meta</a>
            </li>
            <li class="breadcrumb-item active">Seo Meta Update</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box">
<div class="card-header justify-content-between">
        <h4 class="align-middle d-sm-inline-block d-none">Seo Meta Update</h4>
    </div>
  <div class="card-body">
    <?= view('admin/seo/_form', compact('model')) ?>
  </div>
</div>

@endsection