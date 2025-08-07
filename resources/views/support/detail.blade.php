@extends('layouts.main')

@section('title')
    Ticket Details
@endsection

@section('content')
    <?php $user = \Illuminate\Support\Facades\Auth::user(); ?>
    @php
        $statusLabels = [
            1 => 'New',
            2 => 'Open',
            3 => 'Pending',
            4 => 'Solved',
            5 => 'Closed',
            6 => 'Merged',
        ];

        $departments = [
            1 => 'Technical Support',
            2 => 'Billing and Invoices',
            3 => 'Refund and Dispute',
        ];
    @endphp

    <div class="card shadow-sm mb-4" id="ajax-container">
        <div class="justify-content-between d-flex pt-5 pe-5">
            <h4 class="align-middle pt-5 ps-5">Ticket</h4>
            <div>
                <a class="btn btn-dark" href="support">Back</a>
            </div>
        </div>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Title</h5>
                <p class="card-text">{{ $data['title'] }}</p>

                <h5 class="card-title mt-4">Description</h5>
                <p class="card-text">{{ $data['body'] }}</p>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6>Department</h6>
                        <p>{{ $departments[$data['team_id']] ?? 'General Questions' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Status</h6>
                        <p>{{ $statusLabels[$data['status']] }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Date Created</h6>
                        <p>{{ $data['created_at'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Last Updated</h6>
                        <p>{{ $data['updated_at'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="POST" action="{{ url('support/comment-create') }}">
                    @csrf
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                    <input type="hidden" name="id" value="{{ $data['id'] }}">
                    <input type="hidden" name="requester[name]" value="{{ $user->name }}">
                    <input type="hidden" name="requester[email]" value="{{ $user->email }}">
                    <div class="form-group mb-5">
                        <textarea name="body" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Comment As New</button>
                </form>
            </div>
        </div>
        <div class="replies-wrapper">
            <h5 class="fw-bold p-2 mb-2 ms-2">Replies</h5>
            @foreach ($data['comments'] as $comment)
                <div class="border rounded p-3 mb-3 bg-white ms-3">
                    <strong>Admin</strong>
                    <span class="text-muted ms-2">{{ $comment['created_at'] }}</span>
                    <p class="mt-2 mb-0">{{ $comment['body'] }}</p>
                </div>
            @endforeach
            <div class="border rounded p-3 mt-4 bg-white ms-3 mb-5">
                <strong>{{ Auth::user()->first_name }}</strong>
                <span class="text-muted ms-2">{{ $data['created_at'] }}</span>
                <p class="mt-2 mb-0">{{ $data['body'] }}</p>
            </div>
        </div>
    </div>
@endsection
