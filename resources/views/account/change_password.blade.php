@extends('layouts.main')
@section('title')
Change password
@endsection
@section('content')

<?= view('account/account_block',compact('data')); ?>


<div class="card mb-4">
    <h5 class="card-header">Change Password</h5>
    <div class="card-body">
        <form action="account/password-change-process" method="post" class="ajax-form">
            {{ csrf_field() }}
            <div class="row">
                <div class="mb-3 col-md-12 form-password-toggle">
                    <label class="form-label" for="currentPassword">Current Password</label>
                    <div class="input-group-merge">
                        <input class="form-control" maxlength="32" minlength="6" type="password" name="current_password" id="current_password"  required="required"/>
                        
                    </div>
                </div>
                <div class="mb-3 col-md-12 form-password-toggle">
                    <label class="form-label" for="newPassword">New Password</label>
                    <div class="input-group-merge">
                        <input class="form-control" maxlength="32" minlength="6" type="password" id="password" name="password"  required="required"/>
                        
                    </div>
                </div>

                <div class="col-md-12 form-password-toggle">
                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                    <div class="input-group-merge">
                        <input class="form-control"  maxlength="32" minlength="6" type="password" name="confirm_password" id="confirm_password"   required="required"/>
                       
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






<!-- <div class="col-lg-12 col-xl-12">
    <div class="change-pass_wrapper">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <h4>Sets New Password</h4>
            </div>
            <div class="card-body m-0">
                <form action="account/password-change-process" method="post" class="ajax-form">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label>Old Password</label>
                        <div class="position-relative">
                            <input type="password" maxlength="32" minlength="6" name="current_password" class="form-control" id="current_password" placeholder="Old Password" required="required">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <div class="position-relative">
                            <input type="password" maxlength="32" minlength="6" name="password" class="form-control" id="password" placeholder="Password" onclick="showPassword('password')" required="required">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <div class="position-relative">
                            <input type="password" maxlength="32" minlength="6" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary text-white" style="background-color:#685dd8;" name="button">Change Password</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div> -->
<script type="text/javascript">
    documentReady(function() {
        $('.ajax-form').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            }
        })
    });
</script>
@endsection


