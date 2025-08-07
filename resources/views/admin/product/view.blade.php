@extends('admin.layouts.main')

@section('title')
    Product View
@endsection

@section('content')
    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Product View</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin/dashboard') }}" class="pjax">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin/product') }}" class="router pjax">Products</a>
                </li>
                <li class="breadcrumb-item active">Product View</li>
            </ol>
        </nav>
    </div>

    <div class="card" id="ajax-container">
        <div class="card-header justify-content-between d-flex flex-wrap">
            <h4 class="align-middle mb-0">Product View</h4>
            <div>
                <a class="btn btn-dark pjax" href="admin/product">Back</a>
                <a class="btn btn-primary pjax" href="admin/product/update?id={{ $_GET['id'] }}">Edit</a>
            </div>
        </div>

        <div class="card-body p-0" id="ajax-content">
            <table class="table table-striped-columns">
                <tbody>
                    <tr>
                        <th>Title</th>
                        <td>{{ $model->title }}</td>
                    </tr>

                    <tr>
                        <th>Description</th>
                        <td>{!! $model->description !!}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>{{ $model->amount }}</td>
                    </tr>
                    <tr>
                        <th>Image</th>

                        <td>
                            <img class="img-fluid rounded mb-3 pt-1 mt-4"
                                src="{{ $general->getFileUrl($model->image, '2025/05') }} "height="100" width="100"
                                alt="User avatar" />

                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if ($model->status == 0)
                                <span class="badge rounded-pill bg-label-danger">Inactive</span>
                            @else
                                <span class="badge rounded-pill bg-label-success">Active</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $general->dateFormat($model->created_at) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
