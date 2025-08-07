@extends('admin.layouts.main')
@section('title')
Blog View
@endsection
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Blog</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" class="pjax">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/blog/index" class="router pjax">Blog</a>
            </li>
            <li class="breadcrumb-item active">Blog View</li>
        </ol>
    </nav>
</div>
<div class="card" id="ajax-container">
    <div class="card-header flex-wrap" style="display: flex;justify-content: space-between;">
        <div>
            <h4 class="fw-bold  mb-0">Blog View</h4>
        </div>
        <div>
            <a class="btn btn-dark router pjax" href="admin/blog/index">Back</a>
            <a class="btn btn-primary router pjax" href="admin/blog/update?id={{$_GET['id']}}">Edit</a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0" id="ajax-content">
        <table class="table table-striped-columns">
            <tbody>
                <tr>
                    <th>Title</th>
                    <td>{{ $model->title }}</td>
                <tr>
                    <th>Blog category</th>
                    <td>{{$blogCategory->category}}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><?php $blogDesc = strip_tags($model->description);
                        echo $blogDesc ?></td>
                </tr>
                <tr>
                    <th>Tags</th>
                    <td>{{ $model->tags }}</td>
                </tr>

                <tr>
                    <th>Image</th>
                    <td>
                        <img class="img-fluid rounded mb-3 pt-1 mt-4"
                            src="{{ $general->getFileUrl($model->image, 'upload/blog') }} "height="100" width="100"
                            alt="image" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@endsection