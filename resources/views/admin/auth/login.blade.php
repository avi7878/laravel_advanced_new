@extends('admin.layouts.blank')
@section('title')
Login To Your Account
@endsection
@section('content')
<!-- /.login-box -->
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
      <!-- Login -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-6">
            <a href="admin/auth/login" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="{{$general->getFileUrl(config('setting.app_logo'))}}" class="brand-image img-circle elevation-3 preview-app-logo" style="height: 200%;">
              </span>
              <span class="app-brand-text demo text-heading fw-bold">{{ Config::get('setting.app_name') }}</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Welcome to {{ Config::get('setting.app_name') }}</h4>
          <p class="mb-6">Please Log-in to your account</p>
          <form id="login-form" class="mb-4" action="{{ route('admin/auth/login-process') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="email" class="form-label">Email or Username</label>
              <input
                type="text"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter your email or username"
                autofocus />
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  name="password"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>
            <div class="my-8">
              <div class="d-flex justify-content-between">
                <div class="form-check mb-0 ms-2">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember" checked/>
                  <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
                <a href="admin/site/password-forgot">
                  <p class="mb-0">Forgot Password?</p>
                </a>
              </div>
            </div>
            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>
          </form>

          

        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  documentReady(function() {
    $('#login-form').validate({
      rules: {
        email: {
          required: true,
          email: true
        },
        password: {
          minlength: 6,
          maxlength: 32,
          required: true,
        },
      },
      messages: {
        email: {
          required: "Please Enter Your Email.",
          email: "Please enter a valid email address."
        },
        password: {
          required: "Please Enter Your Password",
          minlength: "Please enter at least 6 characters.",
          maxlength: "Please enter no more than 32 characters.",
        }
      },
      submitHandler: function(form) {
        app.ajaxForm(form);
      }
    })
  })
</script>
@endpush