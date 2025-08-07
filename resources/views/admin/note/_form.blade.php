<form action="{{ route('admin/notes/save') }}" method="post" class="ajax-form">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{ @$model->id }}">
    <div class="">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" placeholder="Title" name="title"
                        aria-label="Name" value="{{ @$model->title }}" required/>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Note <span class="text-danger">*</span></label>
                <textarea type="text" id="summernote" class="form-control" name="note" placeholder="Note" required="required">{{ @$model->description }}</textarea>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <button type="submit" class="btn btn-primary me-2">Submit</button>
            <a class="btn btn-dark router pjax" href="admin/notes">Back</button></a>

        </div>
    </div>
</form>

@push('scripts')
<script type="text/javascript">
    documentReady(function() {
        $('.ajax-form').validate({
            submitHandler: function(form) {
                app.ajaxFileForm(form);
            },
            
              rules: {
                title: {
                    required: true
                },
                note: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Please enter the title."
                },
                note: {
                    required: "Please enter the description."
                }
            }
        })
    });
</script>
@endpush
