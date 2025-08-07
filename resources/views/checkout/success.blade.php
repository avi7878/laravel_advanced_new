@extends('layouts.main')

@section('title')
Payment Success
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
        <h1 class="text-success">✅ Payment Successful!</h1>
        <p>Thank you for Subcribe.</p>
    
        <a href="{{ route('plan') }}" class="btn btn-primary mt-3">Return to Homepage</a>
    </div>
</div>
@endsection
