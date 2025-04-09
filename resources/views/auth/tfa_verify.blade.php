@extends('layouts.blank')
@section('title')
Login with Otp
@endsection
@section('content')
<div class="authentication-wrapper authentication-basic px-4">
  <div class="authentication-inner py-4">
    <!--  Two Steps Verification -->
    <div class="card">
      <div class="card-body">
        <!-- Logo -->
        <div class="app-brand justify-content-center mb-4 mt-2">
          <a href="index.html" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
              <img src="{{$general->getFileUrl(config('setting.app_logo'))}}" class="brand-image img-circle elevation-3 preview-app-logo" style="height: 200%;">
            </span>
            <!--<span class="app-brand-text demo text-body fw-bold ms-1">{{ Config::get('setting.app_name') }}</span>-->
          </a>
        </div>
        <!-- /Logo -->
        <h4 class="mb-1 pt-2">Two Step Verification 💬</h4>
        <p class="text-start mb-4">
          We sent a OTP to your Email.
        </p>
        {{ view('common/message_alert') }}
        <p class="mb-0 fw-semibold">Type your 6 digit OTP</p>
        <form class="ajax-form" action="{{route('auth/tfa-verify-process')}}" method="post">
          {{ csrf_field() }}
          <div class="mb-3">
            <label class="form-label">OTP</label>
            <input name="otp" type="number" class="form-control" required maxlength="6" minlength="6" autofocus />
          </div>
          <div class="mb-3">
              <div class="form-check" style="display: flex;justify-content: space-between;">
                <input class="form-check-input" type="checkbox" id="skip_tfa" name="skip_tfa" value="1" checked />
                <label class="form-check-label" for="skip_tfa" style="padding-right: 95px;"> Ignore this device next time </label>
              </div>
            </div>
          <button class="btn btn-primary d-grid w-100 mb-3">Submit</button>
          <div class="form-group mb-8">
              <a href="{{route('logout')}}" class="btn btn-default d-grid w-100 noroute">Logout</a>
            </div>
          <div class="text-center">
            Didn't get the code?
            <a href="javascript:void(0)" onclick="sendOtp();" id="resend-otp-link">Resend</a></div>
        </form>
      </div>
    </div>
    <!-- / Two Steps Verification -->
  </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $('.ajax-form').validate();
  });

    // Send OTP function
    function sendOtp() {
      app.ajaxRequest('{{ route("auth/resend-otp") }}', $('.ajax-form').serialize(), function(response) {
        if (response.status) {
          app.showMessage(response.message, "success");
        } else {
          app.showMessage(response.message, "error");
        }
      });
    }
    // $('.ajax-form').on('submit', function(e) {
    //   e.preventDefault(); // Prevent default form submission
    //   sendOtp(); // Call the sendOtp function
    // });
</script>
@endpush
