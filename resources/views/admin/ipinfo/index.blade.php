@extends('admin.layouts.main')

@section('title')
    IP Info
@endsection

@section('content')

    <div class="breadcrumb-box">
        <h4 class="fw-bold py-3 mb-4">Ip Info</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="admin/dashboard" class="noroute pjax">Dashboard</a>
                </li>
                <li class="breadcrumb-item active pjax">Ip Info</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <h4 class="align-middle mt-5 ps-5">Ip Details</h4>
        <form action="{{ route('admin/ip-info') }}" method="GET" class="mb-4">
            <div class="form-group mb-4 pe-4 ps-4 d-flex justify-content-between">
                <input type="text" name="ip" class="form-control me-2" placeholder="Enter IP address"
                    value="{{ request('ip') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>

            @if ($ipData)
                <div class="card">
                    <div class="card-header">
                        IP Information for: <strong>{{ $ip }}</strong>
                    </div>
                    <div class="card-body">
                        @foreach ($ipData as $key => $value)
                            <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</p>
                        @endforeach
                    </div>
                </div>
        </form>
    </div>
@elseif($ip)
    <div class="alert alert-warning">No data found for IP: {{ $ip }}</div>
    @endif
    </div>
@endsection
