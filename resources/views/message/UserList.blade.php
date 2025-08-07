@extends('layouts.main')
@section('title')
Chats
@endsection
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Chat</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="dashboard" class="noroute">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Chat</li>
        </ol>
    </nav>
</div>

<!-- Invoice List Table -->
<div class="card">
    <div class="card-header justify-content-between">
        <h4 class="align-middle d-sm-inline-block d-none">Chat</h4>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table border-top" id="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td> <!-- Displays the current iteration number -->
                    <td>
                        <img src="{{ $general->getFileUrl($user->image, 'profile') }}"
                            alt="Image"
                            style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    </td>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td> <!-- Display user name -->
                    <td>{{ $user->email }}</td> <!-- Display user email -->
                    <td>
                        <a href="{{ route('message/start', $user->id) }}" class="btn btn-sm btn-primary pjax">
                            <i class="fa fa-comments"></i> Send Message
                        </a> <!-- Action button -->
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsections