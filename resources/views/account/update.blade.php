@extends('layouts.main')
@section('title')
Profile
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
      {{ view('account/component/account_block',compact('model')) }}
    <div class="card mb-6">
        <div class="card-body">
          <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
              <img src="{{ $general->getFileUrl($model->image,'profile') }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" alt="image" height="100px" width="100px" id="uploadedAvatar">
              <div class="button-wrapper">
                  <a onclick="app.showModalView('admin/account/image')" for="upload" class="btn btn-primary me-3 mb-4 text-white" tabindex="0">
                    <span class="d-none d-sm-block">Upload new photo</span>
                    <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                  </a>
                  <button type="reset" class="btn btn-outline-secondary account-image-reset mb-4">
                    <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Reset</span>
                  </button>
                  <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
              </div>
          </div>
      </div>
      <div class="card-body pt-4">
          <form action="account/update-process" method="post" id="ajax-form">
              {{ csrf_field() }}
              <div class="row g-6">
                  <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="first_name" name="first_name" value="{{ $model->first_name }}" autofocus="" placeholder="Enter First Name" required="required" maxlength="128">
                  </div>

                  <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" value="{{ $model->last_name }}" name="last_name" class="form-control" id="last_name" placeholder="Enter Last Name" required="required" maxlength="128">
                  </div>
              
                  <div class="col-md-6">
                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                    <input type="email" value="{{ $model->email }}" name="email" class="form-control" id="email" placeholder="Enter Email" required="required">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label" for="phoneNumber">Phone Number <span class="text-danger">*</span></label>
                    <input type="number" value="{{ $model->phone }}" name="phone" class="form-control" id="phone" placeholder="Enter Phone" required="required">
                  </div>
              </div>
              <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
              </div>
          </form>
      </div>
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