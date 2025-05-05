@extends('admin.layouts.main')
@section('title', 'Setting Update')
@section('content')
<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Setting</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard">Dashboard</a>
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
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Admin Contact Email <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text cursor-pointer"><i
                                                            class="icon-base bx bx-envelope"></i></span>
                                                    <input type="email" id="admin_email" class="form-control"
                                                        placeholder="Admin Contact Email" name="admin_email"
                                                        value="{{ config('setting.admin_email') }}" required />
                                                </div>
                                                <label id="admin_email-error" class="error" for="admin_email"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date Format</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.date_format') }}" name="date_format">
                                                        <option value="Y-m-d"
                                                            <?= config('setting.date_format') == 'Y-m-d' ? 'selected' : '' ?>>
                                                            {{ date('Y-m-d') }}
                                                        </option>
                                                        <option value="d-m-Y"
                                                            <?= config('setting.date_format') == 'd-m-Y' ? 'selected' : '' ?>>
                                                            {{ date('d-m-Y') }}
                                                        </option>
                                                        <option value="m-d-Y"
                                                            <?= config('setting.date_format') == 'm-d-Y' ? 'selected' : '' ?>>
                                                            {{ date('m-d-Y') }}
                                                        </option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Timezone</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.timezone') }}" name="timezone">
                                                        @foreach ($timezonelist as $tzkey => $timezone)
                                                        <option value="{{ $tzkey }}"
                                                            {{ config('app.timezone') == $tzkey ? 'selected' : '' }}>
                                                            {{ $timezone }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date Time Format</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.date_time_format') }}"
                                                        name="date_time_format">
                                                        <option value="Y-m-d h:i A"
                                                            <?= config('setting.date_time_format') == 'Y-m-d h:i A' ? 'selected' : '' ?>>
                                                            {{ date('Y-m-d h:i A') }}
                                                        </option>
                                                        <option value="d-m-Y h:i A"
                                                            <?= config('setting.date_time_format') == 'd-m-Y h:i A' ? 'selected' : '' ?>>
                                                            {{ date('d-m-Y h:i A') }}
                                                        </option>
                                                        <option value="m-d-Y h:i A"
                                                            <?= config('setting.date_time_format') == 'm-d-Y h:i A' ? 'selected' : '' ?>>
                                                            {{ date('m-d-Y h:i A') }}
                                                        </option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Login With OTP</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.user_login_with_otp') }}"
                                                        name="user_login_with_otp">
                                                        <option value="1"
                                                            <?= config('setting.user_login_with_otp') == '1' ? 'selected' : '' ?>>
                                                            Enable</option>
                                                        <option value="0"
                                                            <?= config('setting.user_login_with_otp') == '0' ? 'selected' : '' ?>>
                                                            Disable</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email Verify</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.user_email_verify') }}"
                                                        name="user_email_verify">
                                                        <option value="1"
                                                            <?= config('setting.user_email_verify') == '1' ? 'selected' : '' ?>>
                                                            Enable</option>
                                                        <option value="0"
                                                            <?= config('setting.use   r_email_verify') == '0' ? 'selected' : '' ?>>
                                                            Disable</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Cookie Consent</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.cookie_consent') }}"
                                                        name="cookie_consent">
                                                        <option value="1"
                                                            <?= config('setting.cookie_consent') == '1' ? 'selected' : '' ?>>
                                                            Enable</option>
                                                        <option value="0"
                                                            <?= config('setting.cookie_consent') == '0' ? 'selected' : '' ?>>
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
                            <div class="tab-pane fade" id="navs-top-mail" role="tabpanel">
                                <form action="{{ route('admin/setting/smtp') }}" class="ajax-form-mail" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Host <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text cursor-pointer"><i
                                                            class="icon-base bx bx-envelope"></i></span>
                                                    <input type="text" class="form-control" placeholder="Host" id="host"
                                                        name="host" value="{{ config('mail.mailers.smtp.host') }}"
                                                        required />
                                                </div>
                                                <label id="host-error" class="error" for="host"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Encryption</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.encryption') }}" name="encryption">
                                                        <option value="ssl"
                                                            <?= config('setting.encryption') == 'ssl' ? 'selected' : '' ?>>
                                                            SSL</option>
                                                        <option value="tls"
                                                            <?= config('setting.encryption') == 'tls' ? 'selected' : '' ?>>
                                                            TLS</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Port <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" placeholder="Port" id="port"
                                                        name="port" value="{{ config('mail.mailers.smtp.port') }}"
                                                        required />
                                                </div>
                                                <label id="port-error" class="error" for="port"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Username <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="icon-base bx bx-envelope"></i></span>
                                                    <input type="text" class="form-control" placeholder="Username"
                                                        id="username" name="username"
                                                        value="{{ config('mail.mailers.smtp.username') }}" required />
                                                </div>
                                                <label id="username-error" class="error" for="username"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Password <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" placeholder="Password"
                                                        id="password" name="password"
                                                        value="{{ config('mail.mailers.smtp.password') }}" required />
                                                </div>
                                                <label id="password-error" class="error" for="password"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mail From Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="icon-base bx bx-envelope"></i></span>
                                                    <input type="text" class="form-control" placeholder="Mail From Name"
                                                        id="mail_from_name" name="mail_from_name"
                                                        value="{{ config('setting.mail_from_name') }}" required />
                                                </div>
                                                <label id="mail_from_name-error" class="error" for="mail_from_name"
                                                    style="display:none;"></label>
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
                                                        placeholder="Mail From Address" id="mail_from_address"
                                                        name="mail_from_address"
                                                        value="{{ config('setting.mail_from_address') }}" required />
                                                </div>
                                                <label id="mail_from_address-error" class="error"
                                                    for="mail_from_address" style="display:none;"></label>
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
                                <form action="{{ route('admin/setting/captcha') }}" class="ajax-form-captcha"
                                    method="post">
                                    {{ csrf_field() }}
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Enable</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.google_recaptcha') }}"
                                                        name="google_recaptcha">
                                                        <option value="1"
                                                            <?= config('setting.google_recaptcha') == '1' ? 'selected' : '' ?>>
                                                            Yes</option>
                                                        <option value="0"
                                                            <?= config('setting.google_recaptcha') == '0' ? 'selected' : '' ?>>
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
                                                        value="{{ config('setting.google_recaptcha_secret_key') }}"
                                                        name="google_recaptcha_secret_key" id="secret_key"
                                                        placeholder="google_recaptcha_secret_key">
                                                </div>
                                                <label id="secret_key-error" class="error" for="secret_key"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Public key <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" required
                                                        value="{{ config('setting.google_recaptcha_public_key') }}"
                                                        name="google_recaptcha_public_key" id="public_key"
                                                        placeholder="google_recaptcha_public_key">
                                                </div>
                                                <label id="public_key-error" class="error" for="public_key"
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
                            <div class="tab-pane fade" id="navs-top-login" role="tabpanel">
                                <form action="{{ route('admin/setting/social') }}" class="ajax-form-social"
                                    method="post">
                                    {{ csrf_field() }}
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Google Login</label>
                                                    <select class="form-select" data-style="btn-default"
                                                        value="{{ config('setting.google_login') }}"
                                                        name="google_login">
                                                        <option value="1"
                                                            <?= config('setting.google_login') == '1' ? 'selected' : '' ?>>
                                                            Enable</option>
                                                        <option value="0"
                                                            <?= config('setting.google_login') == '0' ? 'selected' : '' ?>>
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
                                                        value="<?= $setting['services.google_client_id'] ?>" required
                                                        id="client_id" name="google.client_id"
                                                        placeholder="google.client_id">
                                                </div>
                                                <label id="client_id-error" class="error" for="client_id"
                                                    style="display:none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Google Client Secreat <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" required id="client_secret"
                                                        value="<?= $setting['services.google_client_secret'] ?>"
                                                        name="google.client_secret" placeholder="google.client_secret">
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
                                <form action="{{ route('admin/setting/content') }}" class="ajax-form-content"
                                    method="post">
                                    {{ csrf_field() }}
                                    <div class="form-row row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Header</label>
                                                <div class="input-group input-group-merge">
                                                    <textarea class="form-control" rows="8" name="header_content"
                                                        placeholder="Header content"><?= $setting['setting.header_content'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Footer</label>
                                                <div class="input-group input-group-merge">
                                                    <textarea class="form-control" rows="8" name="footer_content"
                                                        placeholder="Footer content"><?= $setting['setting.footer_content'] ?></textarea>
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
                </div>
            </div>
        </div>
    </div>
    <!-- Mail Process start -->
    <div class="modal fade" id="email-test" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin/setting/mailprocess') }}" id="mail-test-form" method="POST"
                    onsubmit="event.preventDefault()">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Mail</h5>
                        <button type="button" class="close closebtnmodal" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="false"><i class="fa-solid fa-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="email" class="form-control" id="email" placeholder="Email Address" name="email"
                            aria-label="Name" required />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
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
    $('.ajax-file-form').validate({
        submitHandler: function(form) {
            app.ajaxFileForm(form);
        }
    })
    $('.ajax-form').validate({
        submitHandler: function(form) {
            app.ajaxForm(form);
        }
    })
    $('.ajax-form-mail').validate({
        rules: {
            host: {
                required: true,

            },
            port: {
                required: true,
            },
            username: {
                required: true,
            },
            password: {
                required: true,
            },
            mail_from_name: {
                required: true,
            },
            mail_from_address: {
                required: true,
            }
        },
        messages: {
            host: {
                required: "Please enter the host.",
            },
            port: {
                required: "Please enter the port.",
            },
            username: {
                required: "Please enter the username.",
            },
            password: {
                required: "Please enter the password.",
            },
            mail_from_name: {
                required: "Please enter the mail from name.",
            },
            mail_from_address: {
                required: "Please enter the mail from address.",
            }
        },
        submitHandler: function(form) {
            app.ajaxFileForm(form);
        },
    })
    $('.ajax-form-captcha').validate({
        rules: {
            google_recaptcha_secret_key: {
                required: true,
            },
            google_recaptcha_public_key: {
                required: true,
            },
        },
        messages: {
            google_recaptcha_secret_key: {
                required: "Please enter the Secret key .",
            },
            google_recaptcha_public_key: {
                required: "Please enter the Public key .",
            },
        },
        submitHandler: function(form) {
            app.ajaxFileForm(form);
        },
    })
    $('.ajax-form-social').validate({
        submitHandler: function(form) {
            app.ajaxFileForm(form);
        },
    })
    $('.ajax-form-content').validate({
        submitHandler: function(form) {
            app.ajaxFileForm(form);
        },
    })


    $('#mail-test-form').validate({
        submitHandler: function(form) {
            app.ajaxForm(form);
        }
    })
});
</script>
@endpush