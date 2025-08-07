<form action="{{ route('note/save') }}" method="post" class="ajax-form">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{ @$model->id }}">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" placeholder="Title" name="title" required="required"
                        aria-label="Name" value="{{ @$model->title }}" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Note <span class="text-danger">*</span></label>
                <textarea type="text" id="summernote" class="form-control" name="note" placeholder="Note" required="required">{{ @$model->description }}</textarea>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <button type="submit" class="btn btn-primary me-2">Submit</button>
            <a class="btn btn-dark router text-white" data-bs-dismiss="modal" aria-label="Close">Cancel</button></a>
        </div>
    </div>
    <div id="form-message" class="mb-3" style="display:none;"></div>
</form>

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('.ajax-form').validate({
        rules: {
            title: {
                required: true,
            },
            note: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title",
            },
            note: {
                required: "Please enter note",
            },
        },
        submitHandler: function(form) {
            var formData = $(form).serialize();
            console.log('Submitting form via AJAX:', formData);
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    console.log('AJAX request is about to be sent');
                },
                success: function(response) {
                    console.log('AJAX save response:', response);
                    if (response.status == 1) {
                        app.showToast(response.message, 'success');
                        $('#form-message').text(response.message).removeClass('text-success').addClass('text-danger').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('#form-message').text('An error occurred: ' + error).removeClass('text-success').addClass('text-danger').show();
                }
            });
            return false;
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.closest('.mb-3'));
        },
    });
});
</script>
@endpush
