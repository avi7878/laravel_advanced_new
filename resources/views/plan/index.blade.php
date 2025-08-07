@extends('layouts.main')

@section('title')
    Plans
@endsection

@section('content')
<div class="container my-5">
    <div class="row text-center">
        @foreach ($plans as $plan)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header  {{ $plan->status }}">
                        <h4 class="my-0 font-weight-normal">{{ $plan->title }}</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">${{ number_format($plan->amount, 2) }} <small class="text-muted">/ {{ $plan->duration }}</small></h1>
                        
                        <p><strong>Description:</strong> {{ $plan->description }}</p>
                        <p><strong>Created on:</strong> {{ $plan->created_at->format('Y-m-d') }}</p>

                        <!--<a href="{{ route('plan-select',['id' => $plan->id])}}" type="button" class="btn btn-lg btn-block btn-outline-primary">-->
                        <!--    Sign up for -->
                        <!--</a>-->
                        
                          <form action="{{ route('plan-select') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="btn btn-lg btn-block btn-outline-primary">
                                Sign up for
                            </button>
                         </form>
                    </div>
                    
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
