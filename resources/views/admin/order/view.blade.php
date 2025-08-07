@extends('admin.layouts.main')

@section('title')
    Order View
@endsection

@section('content')
    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Order View</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="pjax" href="{{ route('admin/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a class="pjax router" href="{{ route('admin/order') }}">Orders</a>
                </li>
                <li class="breadcrumb-item active">Order View</li>
            </ol>
        </nav>
    </div>

    <div class="card" id="ajax-container">
        <div class="card-header justify-content-between d-flex">
            <h4 class="align-middle mb-0">Order View</h4>
            <div>
                <a class="btn btn-dark pjax" href="admin/order">Back</a>
            </div>
        </div>

        <div class="card-body p-0" id="ajax-content">
            <table class="table table-striped-columns">
                <tbody>
                    <tr>
                        <th>UserName</th>
                        <td>{{ $model->first_name . ' ' . $model->last_name }}</td>
                    </tr>
                    <tr>
                        <th>Product Name</th>
                        <td>{{ $model->title }}</td>
                    </tr>
                      <tr>
                        <th>Transaction Id</th>
                        <td>{{ $model->transaction_id }}</td>
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
