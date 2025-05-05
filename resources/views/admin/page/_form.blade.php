<form id="ajax-form" method="post" action="{{ route('admin/page/save') }}">
    @csrf
    <input type="hidden" name="id" value="{{ @$model->id }}">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label" >Title <span class="text-danger">*</span></label>
            <div class="form-group">
                <input type="text" class="form-control" required id="title" placeholder="Title" name="title" aria-label="Name" value="{{ $model->title }}" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="form-label">Body <span class="text-danger">*</span></label>
        <textarea name="body" id="body">{!! $model->body !!}</textarea>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a class="pjax" href="admin/pages"><button type="button" class="btn btn-primary">Back</button></a>
</form>
@push('scripts')
<script type="text/javascript">
documentReady(function() {
    app.addCSS(['https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css']);
    app.loadScript('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js', function() {
        initEditorFull($('#body'),'admin/page/save-file');

        // Trigger validation on summernote change
        $('#body').on('summernote.change', function() {
            $('#body').val($('#body').summernote('code'));
            $('#body').valid();
        });
    });
    jQuery.validator.addMethod("alphaOnly", function(value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Please enter only alphabetic characters");

    jQuery.validator.addMethod("summernoteRequired", function(value, element) {
        var code = $('#body').summernote('isEmpty') ? '' : $('#body').summernote('code');
        var text = $('<div>').html(code).text().trim();
        return text.length > 0;
    }, "Please enter the body");

    $('#ajax-form').validate({
        ignore: [], // <- Important for hidden <textarea>
        rules: {
            title: {
                required: true,
                alphaOnly: true,
                minlength: 2
            },
            body:{
                 summernoteRequired: true,
                 minlength: 2
            }
        },  
        messages: {
            title: {
                required: "Please enter the title",
                minlength: "Please enter at least 2 characters"
            },
            body: {
                summernoteRequired: "Please enter the body",
                minlength: "Please enter at least 2 characters"
            },
        },
        // submitHandler: function(form) {
        //     var postData = new FormData(form);
        //     app.ajaxFileRequest($(form).attr("action"), postData);
        // }

        submitHandler: function(form) {
            $('#body').val($('#body').summernote('code'));
            app.ajaxFileForm(form);
        },

        errorPlacement: function(error, element) {
            if (element.attr("id") == "body") {
                 error.insertAfter(".note-editor");
            } else {
                error.insertAfter(element.closest('.mb-3'));
            }          
        },
       
    })
});
</script>
@endpush