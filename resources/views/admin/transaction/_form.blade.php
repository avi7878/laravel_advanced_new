    {{-- <style>
        .star {
            color: red;
        }

        .input-group.error .input-group-text {
            border-color: #dc3545;
            /* Bootstrap danger color */
        }
    </style>
    <form method="post" action="{{ route('admin/transaction/save') }}" enctype="multipart/form-data" id="ajax-form">
        @csrf
        <input type="hidden" name="id" value="{{ @$model->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="body" for="basic-icon-default-fullname">Data</label>
                            <textarea type="text" id="data" class="form-control" name="data" placeholder="Data"
                                required="required">{{ @$model->data }}</textarea>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Status <span
                                    class="star">*</span></label>
                            <input type="text" class="form-control" id="basic-icon-default-fullname"
                                placeholder="status" name="status" aria-label="status" value="{{ @$model->status }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="admin/transactions" class="btn btn-dark" style="color: white;">Back</a>
    </form>




    @push('scripts')
        <script type="text/javascript">
            documentReady(function() {
                jQuery.validator.addMethod("alphaOnly", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
                }, "Please enter only alphabetic characters");

                $('#ajax-form').validate({
                    rules: {
                        data: {
                            required: true,
                            minlength: 2
                        },
                        status: {
                            required: true,
                        },
                    },
                    messages: {
                        data: {
                            required: "Please enter the data",
                        },
                        status: {
                            required: "Please enter the status",
                        },
                    },

                    submitHandler: function(form) {
                        app.ajaxFileForm(form);
                    },
                });
            });
        </script>
    @endpush --}}
