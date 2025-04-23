@extends('layouts.main')
@section('title')
Profile
@endsection
@section('content')
{{ view('account/component/account_block',compact('model')) }}
<div class="col-xl">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Profile</h5>
    </div>
    <div class="card-body">
      <form action="account/update-process" method="post" id="ajax-form">
        {{ csrf_field() }}
        <div class="mb-3">
          <label class="body" for="basic-default-fullname">First Name <span class="star">*</span></label>
          <input type="text" value="{{ $model->first_name }}" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name" required="required" maxlength="128">
        </div>
        <div class="mb-3">
          <label class="body" for="basic-default-company">Last Name <span class="star">*</span></label>
          <input type="text" value="{{ $model->last_name }}" name="last_name" class="form-control" id="last_name" placeholder="Enter Last Name" required="required" maxlength="128">
        </div>
        <div class="mb-3">
          <label class="body" for="basic-default-email">Email <span class="star">*</span></label>
          <input type="email" value="{{ $model->email }}" name="email" class="form-control" id="email" placeholder="Enter Email" required="required">
        </div>
        <div class="mb-3">
          <label class="body" for="basic-default-email">Phone <span class="star">*</span></label>
          <input type="text" value="{{ $model->phone }}" name="phone" class="form-control" id="phone" placeholder="Enter Phone" required="required">
        </div>
        <button type="submit" class="btn btn-primary text-white" style="background-color:#685dd8;">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
  documentReady(function() {
    $('#ajax-form').validate({
      submitHandler: function(form) {
        app.ajaxForm(form);
      }
    })
  });
</script>
@endpush