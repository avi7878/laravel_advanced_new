@extends('admin.layouts.main')

@section('title')
    Transaction View
@endsection

@section('content')
    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Transaction View</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin/dashboard') }}" class="pjax">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin/transaction') }}" class="router pjax">Transactions</a>
                </li>
                <li class="breadcrumb-item active">Transaction View</li>
            </ol>
        </nav>
    </div>

    <div class="card" id="ajax-container">
        <div class="card-header justify-content-between d-flex flex-wrap">
            <h4 class="align-middle mb-0">Transaction View</h4>
            <div>
                <a class="btn btn-dark pjax" href="admin/transaction">Back</a>
            </div>
        </div>

        <div class="card-body p-0 table-responsive" id="ajax-content">
            <table class="table table-striped-columns">
                <tbody>
                    <tr>
                        <th>Data</th>
                        <td style="word-break: break-all;overflow-wrap: break-word;white-space: normal;">{{ $model->data }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $model->status }}</td>
                    </tr>
                    <tr>
                        <th>Transaction Id</th>
                        <td>{{ $model->stripe_transaction_id }}</td>
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
