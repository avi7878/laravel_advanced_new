@extends('layouts.blank')
@section('title')
Register Your Account
@endsection
@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Register Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4 mt-2">
                        <a href="home" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="{{$general->getFileUrl(config('setting.app_logo'))}}" class="brand-image img-circle elevation-3 preview-app-logo" style="height: 200%;">
                            </span>
                            <!--<span class="app-brand-text demo text-body fw-bold ms-1">{{ Config::get('setting.app_name') }}</span>-->
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1 pt-2" style="text-align: center;">Welcome to {{ Config::get('setting.app_name') }}</h4>
                    <p class="mb-4" style="text-align: center;">Create your account</p>
                    <form class="mb-3" action="{{route('site/register-process')}}" method="POST" id="ajax-form">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">First Name <span class="star">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}" required placeholder="Enter your first name" maxlength="255" autofocus />
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Last Name <span class="star">*</span></label>
                            <input type="text" class="form-control" id="last_name" maxlength="255" name="last_name" required placeholder="Enter your last name"  />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="star">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email" />
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Password <span class="star">*</span></label>
                            <div class=" input-group-merge">
                                <input type="password" id="password" class="form-control" required name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                            </div>
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Confirm Password <span class="star">*</span></label>
                            <div class=" input-group-merge">
                                <input type="password" id="password_confirm" class="form-control" required name="password_confirm" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password_confirm" />
                            </div>
                        </div>

                        <div class="col-12">
                            {{view('common/recaptcha')}}
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <label class="form-check-label" for="terms-conditions">
                                    I agree to
                                    <a target="_blank" href="page/privacy-policy" class="noroute">privacy policy & terms</a>
                                </label>
                                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms"/>
                            </div>
                            <div class="invalid-feedback">
                                You must agree to the privacy policy and terms.
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100">Sign up</button>
                    </form>

                    <p class="text-center">
                        <span>Already have an account?</span>
                        <a href="login">
                            <span>Log in instead</span>
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    documentReady(function() {
        $('#ajax-form').validate({
            rules: 
            {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    minlength: 6,
                    maxlength: 32,
                    required: true
                },
                password_confirm: {
                    equalTo: '#password'
                },
                terms: {
                    required: true
                }
            },
            messages: {
                email: 
                {
                    required: "Please Enter Your Email.",
                    email: "Please enter a valid email address."
                },
                password: {
                    required: "Please Enter Your Password",
                    minlength: "Please enter at least 6 characters.",
                    maxlength: "Please enter no more than 32 characters."
                },
                terms: {
                    required: "You must agree to the privacy policy and terms."
                }
            },
            submitHandler: function(form) {
                app.ajaxForm(form,function(response){
                    grecaptcha.reset();
                    if (response.status) {
                        window.location.href = response.url;
                    }else if (response.message) {
                        app.showMessage(response.message, "error");
                    }
                });
            }
        })
    })
</script>
@endpush