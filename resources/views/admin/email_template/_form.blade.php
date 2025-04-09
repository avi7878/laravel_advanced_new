<style>
     .star{
        color: red;
    }
</style>

<form class="ajax-form" method="post" action="admin/email-template/save" id="ajax-form">
    @csrf
    <input type="hidden" name="id" value="{{ @$model->id }}">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="body" >Title <span class="star">*</span></label>
            <div class="input-group input-group-merge">
                <input type="text" class="form-control" id="title" placeholder="Title" name="title" aria-label="Name" value="{{ $model->title }}" />
            </div>
        </div>
        <div class="mb-3">
            <label class="body" >Subject <span class="star">*</span></label>
            <div class="input-group input-group-merge">
                <input type="text" class="form-control" id="subject" placeholder="subject" name="subject" aria-label="subject" value="{{ $model->subject }}" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="body">Body <span class="star">*</span></label>
        <textarea name="body"  id="summernote">{!! $model->body !!}</textarea>
    </div>
    <div class="mb-3">
            <label class="body mt-2" ><b> Parameters </b></label>
            <div>
                {{$model->params}}
            </div>
        </div>
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a class="pjax" href="admin/email-template"><button type="button" class="btn btn-dark">Back</button></a>
</form>

@push('scripts')
<script type="text/javascript">
documentReady(function() {
    app.addCSS(['https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css']);
    app.loadScript('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js', function() {
        initEditorFull($('#summernote'),'admin/email-template/save-file');
    });
    // $('.ajax-form').validate({
    //     submitHandler: function(form) {
    //         var postData = new FormData(form);
    //         app.ajaxFileRequest($(form).attr("action"), postData);
    //     }
    // });
    
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
                subject: {
                    required: true,
                    minlength: 2
                },
            },
            messages: {
                title: {
                    required: "Please enter the title",
                    minlength: "Please enter at least 2 characters"
                }, 
                subject: {
                    required: "Please enter the subject",
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
});
</script>

@endpush