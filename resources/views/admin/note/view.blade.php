@extends('admin.layouts.main')
@section('title')
Note View
@endsection
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Note View</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" class="pjax">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/notes" class="router pjax">Notes</a>
            </li>
            <li class="breadcrumb-item active">Note View</li>
        </ol>
    </nav>
</div>
<div class="card" id="ajax-container">
    <div class="card-header justify-content-between flex-wrap" style="display: flex;">
        <h4 class="align-middle mb-0">Note View</h4>
        <div>
            <a class="btn btn-dark pjax" href="admin/notes">Back</a>
            <a class="btn btn-primary pjax" href="admin/notes/update?id={{$_GET['id']}}">Edit</a>
        </div>
    </div>
    <div class="card-body p-0" id="ajax-content">
        <table class="table table-striped-columns">
            <tbody>
                <tr>
                    <th>Title</th>
                    <td>{{ $model->title }}</td>
                <tr>
                    <th>Note</th>
                    <td>{!! $model->description !!}</td>
                <tr>
                    <th>Created At</th>
                    <td>{{ date('Y-m-d h:i A', strtotime($model->created_at)); }}</td>

            </tbody>
        </table>
    </div>
</div>
@endsection