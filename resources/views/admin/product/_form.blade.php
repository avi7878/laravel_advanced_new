    <style>
        .input-group.error .input-group-text {
            border-color: #dc3545;
            /* Bootstrap danger color */
        }
    </style>
    <form method="post" action="{{ route('admin/product/save') }}" enctype="multipart/form-data" id="ajax-form">
     @csrf
        <input type="hidden" name="id" value="{{ @$model->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="basic-icon-default-title" placeholder="Title"
                                name="title" aria-label="title" value="{{ @$model->title }}" />
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <?php if (!empty($model->image)) { ?>
                        <img src="{{ $general->getFileUrl($model->image, '2025/05') }}" style="width:100px;height:100px"
                            class="img-fluid" id="image"><br>
                        <?php } else { ?>
                        <img src="{{ $general->getNoFile() }}" class="img-fluid" style="width:100px;height:100px"
                            id="image"><br>
                        <?php  } ?>
                        <div class="form-group">
                            <label>Image</label><br><br>
                            <div class="custom-file">
                                <input type="file" class="form-control custom-file-input" accept="image/*"
                                    name="image" onchange="previewImage(this,'#image')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="body" for="basic-icon-default-fullname">Description <span
                                    class="text-danger">*</span></label>
                            <textarea type="text" id="description" class="form-control" name="description" placeholder="Description"
                                required="required">{{ @$model->description }}</textarea>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Amount <span
                                    class="text-danger">*</span></label>
                            <input type="Number" class="form-control" id="basic-icon-default-fullname"
                                placeholder="Amount" name="amount" aria-label="amount" value="{{ @$model->amount }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Status <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" data-style="btn-default" name="status" aria-label="Status">
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
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="admin/product" class="btn btn-dark pjax" style="color: white;">Back</a>
    </form>




    @push('scripts')
        <script type="text/javascript">
            documentReady(function() {
                jQuery.validator.addMethod("alphaOnly", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
                }, "Please enter only alphabetic characters");

                $('#ajax-form').validate({
                       rules: {
                        title: {
                            required: true,
                            alphaOnly: true,
                            minlength: 2
                        },
                        description: {
                            required: true,
                            minlength: 2
                        },
                        amount: {
                            required: true,
                            number: true,
                            minlength: 1
                        },
                        status: {
                            required: true,
                        },
                    },
                    messages: {
                        title: {
                            required: "Please enter the title",
                            minlength: "Please enter at least 2 characters"
                        },
                        description: {
                            required: "Please enter the description",
                            minlength: "Please enter at least 2 characters"
                        },
                        amount: {
                            required: "Please enter the amount",
                            number: "Please enter a valid number"
                        },
                        status: {
                            required: "Please select the status",
                        },
                    },

                    submitHandler: function(form) {
                        app.ajaxFileForm(form);
                    },
                });
            });
        </script>
    @endpush
