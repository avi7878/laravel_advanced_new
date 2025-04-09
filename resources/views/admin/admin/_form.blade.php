<style>
     .star{
        color: red;
    }
</style>
<form class="ajax-form" method="post" action="{{ route('admin/admin/save') }}" enctype="multipart/form-data" id="ajax-form">
    @csrf
    <input type="hidden" name="id" value="{{ @$model->id }}">
    <input type="hidden" name="pass" value="{{ @$model->password }}">
    <input type="hidden" name="role" value="1">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="body" for="basic-icon-default-fullname">First Name <span class="star">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" aria-label="first_name" value="{{ @$model->first_name }}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="body" for="basic-icon-default-fullname">Last Name <span class="star">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" aria-label="last_name" value="{{ @$model->last_name }}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="body" for="basic-icon-default-fullname">Email <span class="star">*</span></label>
                        <div class="input-group input-group-merge">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email" aria-label="Name" value="{{ @$model->email }}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="body" for="basic-icon-default-password">Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password" autocomplete="new-password" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="body" for="basic-icon-default-fullname">Status</label>
                        <div class="input-group input-group-merge">
                            
                            <select class="form-control" name="status" aria-label="Status">
                                <option value="1" <?php
                                                    if (@$model->status == 1) {
                                                        echo 'selected';
                                                    } ?>>Active</option>
                                <option value="0" <?php if (@$model->status == 0) {
                                    echo 'selected';
                                } ?>>Inactive</option>
                            </select>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-6"><br>
    <?php if (!empty(@$model->image)) { ?>
      
        <img src="{{ $general->getFileUrl(@$model->image,'profile') }}" style="width:100px;height:100px" class="img-fluid" id="image"><br>
        <?php } else { ?>
         <img src="{{ $general->getNoFile() }}" class="img-fluid" style="width:100px;height:100px" id="image"><br>
     <?php  } ?>
     <div class="form-group">
         <label class="body">Image</label><br><br>
         <div class="custom-file">
             <input type="file" class="form-control custom-file-input" accept="image/*" name="image" onchange="previewImage(this,'#image')"><br> 
         </div>
     </div>
 </div>
    <div class="col-md-12">
        <div class="mb-3">
            <label class="body" for="basic-icon-default-fullname">Permission</label>
        </div>
        <div class="row">
            <?php foreach ($model->getPermissionListData() as $permissionList) { ?>
                <div class="col-md-4 checkbox-block mb-3">
                    <label style="display: flex;">
                        <input onchange="$(this).closest('.checkbox-block').find('.checkbox-child').prop('checked',this.checked);" class="checkbox-parent" type="checkbox" name="permission[]" value="{{ $permissionList['key'] }}" <?php echo in_array($permissionList['key'], explode(',', @$model->permission)) ? 'checked' : ''; ?> />
                        <h4 style="margin-bottom: 0%;">{{ $permissionList['title'] }}</h4>
                    </label>
                    <div class="checkbox-items">
                        <?php if(isset($permissionList['list']) && $permissionList['list']){foreach ($permissionList['list'] as $permission) { ?>
                            <label>
                                <input onchange="if(this.checked){$(this).closest('.checkbox-block').find('.checkbox-parent').prop('checked',true);}" class="checkbox-child" type="checkbox" name="permission[]" value="{{ $permission['key'] }}" <?php echo in_array($permission['key'], explode(',', @$model->permission)) ? 'checked' : ''; ?> />
                                {{ $permission['title'] }}
                            </label><br />
                        <?php }} ?>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="admin/admin" class="btn btn-dark" style="color: white">Back</a>
</form>

@push('scripts')
<script type="text/javascript">
    documentReady(function() {
        // Add a custom method to validate alphabetic characters
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
                    minlength: 6,
                    maxlength: 10
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
            },

            submitHandler: function(form) {
                app.ajaxFileForm(form);
            },
            
            errorPlacement: function(error, element) {
                // Place the error message under the input field
                error.insertAfter(element.closest('.mb-3'));
            },
        });

        // function previewImage(file) {
        //     // Implement code to display preview of selected image
        // }
    });
</script>


@endpush