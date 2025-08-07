@extends('layouts.main')

@section('title')
Support
@endsection

@section('content')
@php
$user = Auth::user();

$statusLabels = [
1 => ['text' => 'New'],
2 => ['text' => 'Open'],
3 => ['text' => 'Pending'],
4 => ['text' => 'Solved'],
5 => ['text' => 'Closed'],
6 => ['text' => 'Merged'],
];

$departments = [
1 => 'Technical Support',
2 => 'Billing and Invoices',
3 => 'Refund and Dispute',
];
@endphp

<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Support</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="dashboard" class="noroute">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Supports</li>
        </ol>
    </nav>
</div>

<div class="card">
      <div class="row card-header flex-column flex-sm-row ">
        <div class="d-flex justify-content-between align-items-center dt-layout-start col-sm-auto me-auto mt-0">
             <h4 class="align-middle mb-0 ">Supports</h4>
        </div>
        <div class="d-flex justify-content-between align-items-center dt-layout-end col-auto ms-auto mt-0">
            <div class="dt-buttons btn-group flex-wrap mb-0">
                <a href="support/new-ticket" class="btn btn-primary router text-white pjax" style="float: inline-end;">New Ticket</a>
            </div>
        </div> 
    </div>

    <div class="card-datatable table-responsive" id="pagination-ajax-container">
        <table class=" table border-top" id="data-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Department</th>
                    <th>Last Update</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="ticketLists">
                @foreach ($data as $ticket)
                <tr class="ticket-row">
                    <td>{{ $ticket['id']}}</td>
                    <td>{{ $ticket['title'] }}</td>
                    <td>{{ $statusLabels[$ticket['status']]['text'] }}</td>
                    <td>{{ $departments[$ticket['team_id']] ?? 'General Questions' }}</td>
                    <td>{{ $ticket['updated_at'] }}</td>
                    <td>{{ $ticket['created_at'] }}</td>
                    <td>
                        <a class="text-body router" href="{{ url('support/ticket-detail', ['id' => $ticket['id']]) }}">
                            <i class="bx bxs-show icon-base"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
       
    </div>
     <div class="d-flex justify-content-between align-items-center mt-3 px-3 mb-3">
            <div>Page {{ $currentPage }} of {{ $totalPages }}</div>
            <div>
                @if ($currentPage > 1)
                    <a href="{{ url('support') }}?page={{ $currentPage - 1 }}" class="btn btn-outline-primary btn-sm">Previous</a>
                @endif

                @if ($currentPage < $totalPages)
                    <a href="{{ url('support') }}?page={{ $currentPage + 1 }}" class="btn btn-outline-primary btn-sm">Next</a>
                @endif
            </div>
        </div>
</div>

@endsection