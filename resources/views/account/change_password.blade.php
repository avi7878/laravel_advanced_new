@extends('layouts.main')
@section('title')
Change password
@endsection
@section('content')
{{ view('account/component/account_block',compact('model')) }}
<div class="card mb-4">
    <h5 class="card-header">Change Password</h5>
    <div class="card-body">
        <form action="account/password-change-process" method="post" id="ajax-form">
            {{ csrf_field() }}
            <div class="row">
                <div class="mb-3 col-md-12 form-password-toggle">
                    <label class="form-label" for="currentPassword">Current Password</label>
                    <div class="input-group-merge">
                        <input class="form-control" maxlength="32" minlength="6" type="password" name="current_password" id="current_password" required="required" />

                    </div>
                </div>
                <div class="mb-3 col-md-12 form-password-toggle">
                    <label class="form-label" for="newPassword">New Password</label>
                    <div class="input-group-merge">
                        <input class="form-control" maxlength="32" minlength="6" type="password" id="password" name="password" required="required" />

                    </div>
                </div>

                <div class="col-md-12 form-password-toggle">
                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                    <div class="input-group-merge">
                        <input class="form-control" maxlength="32" minlength="6" type="password" name="confirm_password" id="confirm_password" required="required" />

                    </div>
                </div><br>
                <div style="padding-top: 17px;">
                    <button type="submit" class="btn btn-primary me-2">Save changes</button>
                    <button type="reset" class="btn btn-dark">Cancel</button>
                </div>
            </div>
        </form>
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