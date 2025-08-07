@extends('layouts.main')

@section('title')
Payment Cancel
@endsection

@section('content')

<style>
    .card{
        max-width: 600px;
        width: 100%;
        margin: auto;
        height: 346px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="card">
    <div class="container text-center mt-5">
          <h1 class="text-danger">❌ Payment Cancelled</h1>
        <p>You have cancelled the payment or something went wrong.</p>
    
        <a href="{{ route('plan') }}" class="btn btn-primary mt-3">Try Again</a>
    </div>
</div>
@endsection
