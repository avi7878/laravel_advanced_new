@extends('layouts.main')
@section('title')
Change password
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
    {{ view('account/component/account_block',compact('model')) }}
    <div class="card mb-6">
        <h5 class="card-header">Change Password</h5>
        <div class="card-body">
            <form action="account/password-change-process" method="post" id="ajax-form" class="fv-plugins-bootstrap5 fv-plugins-framework">
                {{ csrf_field() }}
                <div class="row gx-6">
                    
                    <div class="mb-4 col-12 col-sm-4 form-password-toggle">
                        <label class="form-label" for="currentPassword">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group-merge">
                            <input class="form-control" maxlength="32" minlength="6" type="password" name="current_password" id="current_password" required="required" />
                        </div>
                    </div>
                    
                    <div class="mb-3 col-12 col-sm-4 form-password-toggle">
                        <label class="form-label" for="newPassword">New Password <span class="text-danger">*</span></label>
                        <div class="input-group-merge">
                            <input class="form-control" maxlength="32" minlength="6" type="password" id="password" name="password" required="required" />
                        </div>
                    </div>
                    <div class="mb-3 col-12 col-sm-4 form-password-toggle">
                        <label class="form-label" for="confirmPassword">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group-merge">
                            <input class="form-control" maxlength="32" minlength="6" type="password" name="confirm_password" id="confirm_password" required="required" />
                        </div>
                    </div>
                    <div style="padding-top: 17px;">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <button type="reset" class="btn btn-dark">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    documentReady(function() {
        $('#ajax-form').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            }
        })
    });
</script>
@endsection