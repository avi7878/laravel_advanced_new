<style>
    .star{
        color: red;
    }
</style>
<form class="ajax-form" method="post" action="{{ route('admin/user/save') }}" enctype="multipart/form-data" id="ajax-form">
    @csrf
    <input type="hidden" name="id" value="{{ @$model->id }}">
    <input type="hidden" name="pass" value="{{ @$model->password }}">
    <input type="hidden" name="role" value="2">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">First Name <span class="star">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" id="basic-icon-default-first-name" placeholder="First Name" name="first_name" aria-label="first_name" value="{{ @$model->first_name }}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Last Name <span class="star">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" id="basic-icon-default-last-name" placeholder="Last Name" name="last_name" aria-label="Name" value="{{ @$model->last_name }}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Email <span class="star">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="email" class="form-control" id="basic-icon-default-fullname" placeholder="Email" name="email" aria-label="Name" value="{{ @$model->email }}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-password">Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control" id="basic-icon-default-password" placeholder="Password" name="password" autocomplete="new-password" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Status <span class="star">*</span></label>
                        <div class="input-group input-group-merge">

                            <select class="form-control" name="status" aria-label="Status">
                                <option value="1" <?php if (@$model->status == 1) {
                                                        echo 'selected';
                                                    } ?>>Active</option>
                                <option value="0" <?php if (@$model->status == 0) {
                                                        echo 'selected';
                                                    } ?>>Inactive</option>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Country</label>
                        <div class="input-group input-group-merge">

                            <input type="text" class="form-control" id="basic-icon-default-fullname" placeholder="Country" name="country" aria-label="Country" value="{{ @$model->country }}" />

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6"><br>
            <?php if (!empty($model->image)) { ?>
                <img src="{{ $general->getFileUrl($model->image,'profile') }}" style="width:100px;height:100px" class="img-fluid" id="image"><br>
            <?php } else { ?>
                <img src="{{ $general->getNoFile() }}" class="img-fluid" style="width:100px;height:100px" id="image"><br>
            <?php  } ?>
            <div class="form-group">
                <label>Image</label><br><br>
                <div class="custom-file">
                    <input type="file" class="form-control custom-file-input" accept="image/*" name="image" onchange="previewImage(this,'#image')">
                </div>
            </div>
        </div>

    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  <a href="admin/users" class="btn btn-dark" style="color: white;">Back</a>
</form>




@push('scripts')
<script type="text/javascript">
    documentReady(function() {
        jQuery.validator.addMethod("alphaOnly", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        }, "Please enter only alphabetic characters");

        $('#ajax-form').validate({
            rules: {
                first_name: {
                    required: true,
                    alphaOnly: true,
                    minlength: 2
                },
                last_name: {
                    required: true,
                    alphaOnly: true,
                    minlength: 2
                },
                password: {
                    minlength: 6
                },
                email: {
                    required: true,
                    email: true,
                },
                status: {
                    required: true,
                },
            },
             messages: {
                first_name: {
                    required: "Please enter the first name",
                    minlength: "Please enter at least 2 characters"
                },
                last_name: {
                    required: "Please enter the last name",
                    minlength: "Please enter at least 2 characters"
                },
                email: {
                    required: "Please enter the email",
                    email: "Please enter a valid email address",
                },
                status: {
                    required: "Please select the status",
                },
            },

            submitHandler: function(form) {
                app.ajaxFileForm(form);
            },

            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.mb-3'));
            },
        });
    });
</script>
@endpush