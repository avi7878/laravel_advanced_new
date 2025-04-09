@extends('layouts.blank')
@section('title')
Forgot  Password
@endsection
@section('content')
<style>
    .star{
        color: red;
    }
</style>
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6">
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-6">
                        <a href="auth/password-forgot" class="app-brand-link">
                          <span class="app-brand-logo demo">
                            <img src="{{$general->getFileUrl(config('setting.app_logo'))}}" class="brand-image img-circle elevation-3 preview-app-logo" style="height: 200%;">
                          </span>
                          <span class="app-brand-text demo text-heading fw-bold">{{ Config::get('setting.app_name') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1" >Forgot Password</h4>
                    <p class="mb-6" >Enter your email and we'll send you instructions to reset your password</p>
                      {{ view('common/message_alert') }}
                    <form class="ajax-form" action="{{ route('auth/password-forgot-process') }}" class="mb-6 fv-plugins-bootstrap5 fv-plugins-framework" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="step" id="step" value="1">
                        <div class="mb-6 email-block">
                            <label class="form-label">Email <span class="star">*</span></label>
                            <input id="email" type="email" class="form-control" name="email" placeholder="Enter your email" autofocus value="" />
                            <div class="col-12 recaptcha-block">
                                {{view('common/recaptcha')}}
                            </div>
                        </div>
                        <div class="mb-6 otp-block" style="display: none;">
                            <label class="form-label">OTP <span class="star">*</span></label>
                            <input type="text" class="form-control" name="otp" placeholder="Enter your otp" autofocus value="" />
                            <div class="text-center">
                                <br>
                                Didn't get the code?
                                <a href="javascript:void(0)" onclick="resendOtp($('#email').val())" id="resend-otp-link">Resend</a>
                            </div>
                        </div>
                        <div class="mb-6 password-block" style="display: none;">
                            <div class="mb-6 ">
                                <label class="form-label">Password <span class="star">*</span></label>
                                <input type="password" class="form-control" name="password" placeholder="Enter your email" autofocus value="" />
                            </div>
                            <div class="mb-6 ">
                                <label class="form-label">confirm password <span class="star">*</span></label>
                                <input type="password" class="form-control" name="password_confirm" placeholder="Enter your email" value="" />
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary d-grid w-100 waves-effect waves-light">Send OTP</button>
                    </form>
                     <br>
                     <div class="text-center">
                        <a href="{{ route('admin/auth/login') }}" class="d-flex justify-content-center">
                            <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                            Back to login
                        </a>
                    </div>
              </div>
            <!-- /Forgot Password -->
        </div>
    </div>
</div>
<!-- /.login-box -->
@endsection
@push('scripts')
<script>
    documentReady(function() {
        $('.ajax-form').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                email: {
                    required: "Please Enter Your Email.",
                    email: "Please enter a valid email address."
                },
            },
            submitHandler: function(form) {
                app.ajaxForm(form,function(response){
                    if($('#step').val()=='1'){
                        try{
                            grecaptcha.reset();
                        }catch(e){}
                    }
                    if (response.status) {
                        if (response.message) {
                            app.showConfirmationPopup({title:"", text:response.message, type:"success"}).then(function() {
                                if(response.next=='step_2'){
                                    $('.otp-block').show();
                                    $('.email-block').hide();
                                    $('#step').val('2');
                                }else if(response.next=='step_3'){
                                    $('.password-block').show();
                                    $('.otp-block').hide();
                                    $('#step').val('3');
                                }else if(response.next=='redirect'){
                                    window.location.href = response.url;
                                }
                            });
                        }
                    }else if (response.message) {
                        app.showMessage(response.message, "error");
                    }
                });
            }
        })
    })
    function resendOtp(email){
        app.ajaxPost('{{ route('auth/resend-otp') }}',{type:'forgot_password',code:btoa(email)})
    }
</script>
@endpush