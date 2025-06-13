@extends('admin.layouts.main')
@section('title', 'Setting Update')
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Setting</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" class="pjax">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Setting</li>
        </ol>
    </nav>
</div>
<div class="content-wrapper">
    <!-- Content -->
    <!-- Tabs -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-start">
                    <h5 class="">Setting Update</h5>
                    <div>
                        <button type="submit" class="btn btn-primary"
                            onclick="app.ajaxGet('{{ route('admin/setting/cache-clear') }}')">
                            Clear Cache
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="nav-align-top nav-tabs-shadow mb-6">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-general" aria-controls="navs-top-general"
                                    aria-selected="true">
                                    General
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-logo" aria-controls="navs-top-logo" aria-selected="false">
                                    Logo
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-mail" aria-controls="navs-top-mail" aria-selected="false">
                                    Mail
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-recaptcha" aria-controls="navs-top-recaptcha"
                                    aria-selected="false">
                                    Google recaptcha
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-login" aria-controls="navs-top-login"
                                    aria-selected="false">
                                    Social Login
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-content" aria-controls="navs-top-content"
                                    aria-selected="false">
                                    Content
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="navs-top-general" role="tabpanel"
                                aria-labelledby="navs-top-general">
                                <form action="{{ route('admin/setting/save') }}" class="ajax-form" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="type" value="general">
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">App Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="">
                                                    <input type="text" class="form-control" placeholder="App Name" id="setting_app_name"
                                                        name="setting_app_name" value="{{ $setting['setting.app_name'] }}"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Admin Contact Email <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text cursor-pointer"><i
                                                            class="icon-base bx bx-envelope"></i></span>
                                                    <input type="email" id="setting_admin_email" class="form-control"
                                                        placeholder="Admin Contact Email" name="setting_admin_email"
                                                        value="{{ $setting['setting.admin_email'] }}" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date Format</label>
                                                <select class="form-select" data-style="btn-default"
                                                    value="{{ $setting['setting.date_format'] }}" name="setting_date_format">
                                                    <option value="Y-m-d"
                                                        {{ $setting['setting.date_format'] == 'Y-m-d' ? 'selected' : '' }}>
                                                        {{ date('Y-m-d') }}
                                                    </option>
                                                    <option value="d-m-Y"
                                                        {{ $setting['setting.date_format'] == 'd-m-Y' ? 'selected' : '' }}>
                                                        {{ date('d-m-Y') }}
                                                    </option>
                                                    <option value="m-d-Y"
                                                        {{ $setting['setting.date_format'] == 'm-d-Y' ? 'selected' : '' }}>
                                                        {{ date('m-d-Y') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date Time Format</label>
                                                <select class="form-select" data-style="btn-default"
                                                    value="{{ $setting['setting.date_time_format'] }}"
                                                    name="setting_date_time_format">
                                                    <option value="Y-m-d h:i A"
                                                        {{ $setting['setting.date_time_format'] == 'Y-m-d h:i A' ? 'selected' : '' }}>
                                                        {{ date('Y-m-d h:i A') }}
                                                    </option>
                                                    <option value="d-m-Y h:i A"
                                                        {{ $setting['setting.date_time_format'] == 'd-m-Y h:i A' ? 'selected' : '' }}>
                                                        {{ date('d-m-Y h:i A') }}
                                                    </option>
                                                    <option value="m-d-Y h:i A"
                                                        {{ $setting['setting.date_time_format'] == 'm-d-Y h:i A' ? 'selected' : '' }}>
                                                        {{ date('m-d-Y h:i A') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Login With OTP</label>
                                                <select class="form-select" data-style="btn-default"
                                                    value="{{ $setting['setting.user_login_with_otp'] }}"
                                                    name="setting_user_login_with_otp">
                                                    <option value="1"
                                                        {{ $setting['setting.user_login_with_otp'] == '1' ? 'selected' : '' }}>
                                                        Enable</option>
                                                    <option value="0"
                                                        {{ $setting['setting.user_login_with_otp'] == '0' ? 'selected' : '' }}>
                                                        Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Cookie Consent</label>
                                                <select class="form-select" data-style="btn-default"
                                                    value="{{ $setting['setting.cookie_consent'] }}"
                                                    name="setting_cookie_consent">
                                                    <option value="1"
                                                        {{ $setting['setting.cookie_consent'] == '1' ? 'selected' : '' }}>
                                                        Enable</option>
                                                    <option value="0"
                                                        {{ $setting['setting.cookie_consent'] == '0' ? 'selected' : '' }}>
                                                        Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email Verify</label>
                                                <select class="form-select" data-style="btn-default"
                                                    value="{{ $setting['setting.user_email_verify'] }}"
                                                    name="setting_user_email_verify">
                                                    <option value="1"
                                                        {{ $setting['setting.user_email_verify'] == '1' ? 'selected' : '' }}>
                                                        Enable</option>
                                                    <option value="0"
                                                        {{ $setting['setting.user_email_verify'] == '0' ? 'selected' : '' }}>
                                                        Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="navs-top-logo" role="tabpanel">
                                <div class="row" style="margin-left:0%">
                                    <div class="col-6">
                                        <form action="{{ route('admin/setting/save-logo') }}" class="ajax-file-form-logo" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="key" value="setting.app_logo">
                                            <div class="form-row row">
                                                <div class="col-md-12">
                                                    <div id="ajax-content">
                                                        <div class="col-sm-6 col-lg-4 mb-4">
                                                            <label class="form-label">App Logo <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="col-sm-6 col-lg-4 mb-4">
                                                                <div class="card">
                                                                    <img class="card-img-top preview-app-logo"
                                                                        src="{{ $general->getFileUrl($setting['setting.app_logo'], 'logo') }}"
                                                                        alt="Card image cap" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <div class="input-group input-group-merge">
                                                                    <input type="file" required="required"
                                                                        name="image"
                                                                        onchange="previewImage(this,'.preview-app-logo')"
                                                                        class="form-control" accept="image/*"
                                                                        id="applogo">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <form action="{{ route('admin/setting/save-logo') }}" class="ajax-file-form-favicon" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="key" value="setting.app_favicon">
                                            <div class="form-row row">
                                                <div class="col-md-12">
                                                    <div id="ajax-content">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">App Favicon <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="col-sm-6 col-lg-4 mb-4">
                                                                    <div class="card">
                                                                        <img class="card-img-top preview-app-fevicon"
                                                                            src="{{ $general->getFileUrl($setting['setting.app_favicon'], 'logo') }}"
                                                                            alt="Card image cap" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <div class="input-group input-group-merge">
                                                                    <input type="file" required name="image"
                                                                        onchange="previewImage(this,'.preview-app-fevicon')"
                                                                        class="form-control" accept="image/*"
                                                                        id="input-app-fevicon">
                                                                </div>
                                                                <label id="input-app-fevicon-error" for="input-app-fevicon" class="error"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-top-mail" role="tabpanel">
                                <form action="{{ route('admin/setting/save') }}" class="ajax-form-mail" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="type" value="smtp">
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Host <span
                                                        class="text-danger">*</span></label>
                                                <div class="">
                                                    <input type="text" class="form-control" placeholder="Host"
                                                        name="mail_mailers_smtp_host" value="{{ $setting['mail.mailers.smtp.host'] }}"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Encryption</label>
                                                <select class="form-select" data-style="btn-default"
                                                    value="{{ $setting['mail.mailers.smtp.encryption'] }}" name="mail_mailers_smtp_encryption">
                                                    <option value="ssl"
                                                        {{ $setting['mail.mailers.smtp.encryption'] == 'ssl' ? 'selected' : '' }}>
                                                        SSL</option>
                                                    <option value="tls"
                                                        {{ $setting['mail.mailers.smtp.encryption'] == 'tls' ? 'selected' : '' }}>
                                                        TLS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Port <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" placeholder="Port"
                                                        name="mail_mailers_smtp_port" value="{{ $setting['mail.mailers.smtp.port'] }}"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Username <span
                                                        class="text-danger">*</span></label>
                                                <div class="">
                                                    <input type="text" class="form-control" placeholder="Username"
                                                        id="mail.mailers.smtp.username" name="mail_mailers_smtp_username"
                                                        value="{{ $setting['mail.mailers.smtp.username'] }}" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Password <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" placeholder="Password"
                                                        name="mail_mailers_smtp_password"
                                                        value="{{ $setting['mail.mailers.smtp.password'] }}" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mail From Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="">
                                                    <input type="text" class="form-control" placeholder="Mail From Name"
                                                        name="mail_from_name"
                                                        value="{{ $setting['mail.from.name'] }}" required />
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mail From Address <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="icon-base bx bx-envelope"></i></span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Mail From Address"
                                                        name="mail_from_address"
                                                        value="{{ $setting['mail.from.address'] }}" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group form-submail">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#email-test">Send Test Email</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="navs-top-recaptcha" role="tabpanel">
                                <form action="{{ route('admin/setting/save') }}" class="ajax-form-captcha"
                                    method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="type" value="captcha">
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Enable</label>
                                                <select class="form-select" data-style="btn-default"
                                                    value="{{ $setting['setting.google_recaptcha'] }}"
                                                    name="setting_google_recaptcha">
                                                    <option value="1"
                                                        {{ $setting['setting.google_recaptcha'] == '1' ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="0"
                                                        {{ $setting['setting.google_recaptcha'] == '0' ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Secret key <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" required
                                                        value="{{ $setting['setting.google_recaptcha_secret_key'] }}"
                                                        name="setting_google_recaptcha_secret_key" id="secret_key"
                                                        placeholder="google_recaptcha_secret_key">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Public key <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" required
                                                        value="{{ $setting['setting.google_recaptcha_public_key'] }}"
                                                        name="setting_google_recaptcha_public_key" id="public_key"
                                                        placeholder="google recaptcha public key">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="navs-top-login" role="tabpanel">
                                <form action="{{ route('admin/setting/save') }}" class="ajax-form-social"
                                    method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="type" value="social">
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Google Login</label>
                                                <select class="form-select" data-style="btn-default"
                                                    value="{{ $setting['setting.google_login'] }}"
                                                    name="setting_google_login">
                                                    <option value="1"
                                                        {{ $setting['setting.google_login'] == '1' ? 'selected' : '' }}>
                                                        Enable</option>
                                                    <option value="0"
                                                        {{ $setting['setting.google_login'] == '0' ? 'selected' : '' }}>
                                                        Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Google Client ID <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control"
                                                        value="{{ $setting['services.google_client_id'] }}" required
                                                        name="services_google_client_id"
                                                        placeholder="Google client id">
                                                </div>
                                                <label id="client_id-error" class="error" for="client_id"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Google Client Secret <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" required
                                                        value="{{ $setting['services.google_client_secret'] }}"
                                                        name="services_google_client_secret" placeholder="Google client secret">
                                                </div>
                                                <label id="client_secret-error" class="error" for="client_secret"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="navs-top-content" role="tabpanel">
                                <form action="{{ route('admin/setting/save') }}" class="ajax-form-content"
                                    method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="type" value="content">
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Header</label>
                                                <textarea class="form-control" rows="8" name="setting_header_content"
                                                    placeholder="Header content">{{ $setting['setting.header_content'] }}</textarea>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Footer</label>
                                                <textarea class="form-control" rows="8" name="setting_footer_content"
                                                    placeholder="Footer content">{{ $setting['setting.footer_content'] }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mail Process start -->
    <div class="modal fade" id="email-test" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin/setting/mail-process') }}" class="ajax-form-mail-test" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Mail</h5>
                        <button type="button" class="close closebtnmodal" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="false"><i class="fa-solid fa-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" placeholder="Email Address" name="email" aria-label="Name" required />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-dark btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- mail Process End -->
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    documentReady(function() {
        $('.ajax-file-form-logo').validate({
            submitHandler: function(form) {
                app.ajaxFileForm(form);
            }
        })
        $('.ajax-file-form-favicon').validate({
            submitHandler: function(form) {
                app.ajaxFileForm(form);
            },
        })
        $('.ajax-form').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            }
        })
        $('.ajax-form-mail').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            },
        })
        $('.ajax-form-captcha').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            },
        })
        $('.ajax-form-social').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            },
        })
        $('.ajax-form-content').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            },
        })
        $('.ajax-form-mail-test').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            }
        })
    });
</script>
@endpush