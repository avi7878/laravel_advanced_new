<style>
.input-group.error .input-group-text {
    border-color: #dc3545;
    /* Bootstrap danger color */
}
    </style>
    <form method="post" action="{{ route('admin/user/save') }}" enctype="multipart/form-data" id="ajax-form">
        @csrf
        <input type="hidden" name="id" value="{{ @$model->id }}">
        <input type="hidden" name="pass" value="{{ @$model->password }}">
            <input type="hidden" name="role" value="4">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">First Name <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="basic-icon-default-first-name"
                                    placeholder="First Name" name="first_name" aria-label="first_name"
                                    value="{{ @$model->first_name }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Last Name <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="basic-icon-default-last-name"
                                    placeholder="Last Name" name="last_name" aria-label="Name"
                                    value="{{ @$model->last_name }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Email <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="email" class="form-control" id="basic-icon-default-fullname"
                                    placeholder="Email" name="email" aria-label="Name" value="{{ @$model->email }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-password-toggle form-control-validation">
                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge has-validation">
                                <input type="password" class="form-control" id="password"
                                    placeholder="Password" name="password" autocomplete="password"  value="{{ @$model->password }}"  />
                                     <span class="input-group-text cursor-pointer">
                                              <i class="icon-base bx bx-hide"></i></span>
                            </div>
                             <label id="password-error" class="error" for="password" style="display:none;"></label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Status <span
                                    class="text-danger">*</span></label>
                                <select class="form-select" data-style="btn-default" name="status"
                                    aria-label="Status">
                                    <option value="1" <?php if (@$model->status == 1) {
                                                            echo 'selected';
                                                        } ?>>Active</option>
                                    <option value="0" <?php if (@$model->status == 0) {
                                                            echo 'selected';
                                                        } ?>>Inactive</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Country</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="basic-icon-default-fullname"
                                    placeholder="Country" name="country" aria-label="Country"
                                    value="{{ @$model->country }}" />
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Phone Number <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="number" class="form-control" id="basic-icon-default-phone-number"
                                    placeholder="Phone Number" name="phone" aria-label="Name"
                                    value="{{ @$model->phone }}" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6 mb-3"><br>
                <?php if (!empty($model->image)) { ?>
                <img src="{{ $general->getFileUrl($model->image,'profile') }}" style="width:100px;height:100px"
                    class="img-fluid" id="image"><br>
                <?php } else { ?>
                <img src="{{ $general->getNoFile() }}" class="img-fluid" style="width:100px;height:100px"
                    id="image"><br>
                <?php  } ?>
                <div class="form-group">
                    <label>Image</label><br><br>
                    <div class="custom-file">
                        <input type="file" class="form-control custom-file-input" accept="image/*" name="image"
                            onchange="previewImage(this,'#image')">
                    </div>
                </div>
            </div>

        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="admin/user" class="btn btn-dark pjax" style="color: white;">Back</a>
    </form>

    @push('scripts')
    <script type="text/javascript">
    jQuery.validator.addMethod("noDisposableEmail", v => !["mailinator.com","tempmail.com","10minutemail.com","guerrillamail.com","fakeinbox.com"].includes((v.split('@')[1]||"").toLowerCase()), "Disposable email addresses are not allowed.");
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
                    required: true,
                    minlength: 6,
                },
                email: {
                    required: true,
                    email: true,
                    noDisposableEmail: true
                },
                status: {
                    required: true,
                },
                phone: {
                    required: true,
                    minlength: 10
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
                    noDisposableEmail: "Please enter a valid Email domain"
                },
                password: {
                    required: "Please enter the password",
                    minlength: "Please enter at least 6 characters"
                },
                status: {
                    required: "Please select the status",
                },
                phone: {
                    required: "Please enter the phone number",
                    minlength: "Please enter at least 10 digits"
                },
            },

            submitHandler: function(form) {
                app.ajaxFileForm(form);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
                $(element)
                    .closest('.input-group')
                    .find('.input-group-text')
                    .addClass('error');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
                $(element)
                    .closest('.input-group')
                    .find('.input-group-text')
                    .removeClass('error');
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.input-group-merge'));
            },
        });
    });
    </script>
    @endpush