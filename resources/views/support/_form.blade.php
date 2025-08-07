
   <?php $user = \Illuminate\Support\Facades\Auth::user(); ?>
<form action="{{ route('support/save') }}" method="post" class="ajax-form">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{ @$model->id }}" >
    <div class="row">
         <input type="hidden" name="_token" value="{{ Session::token() }}">
         <input type="hidden" name="requester[name]" value="{{ ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '') }}">
         <input type="hidden" name="requester[email]" value="{{ @$user['email'] }}">
        <div class="col-md-12">
            <div class="mb-3">
            <label for="department" class="form-label">Select Department <span class="text-danger">*</span></label>
                <select required name="team_id" id="department" class="form-select" required >
                    <option value="">Select Department</option>
                    <option value="1" {{ @$model->team_id == 1 ? 'selected' : '' }}>Technical Support</option>
                    <option value="2" {{ @$model->team_id == 2 ? 'selected' : '' }}>Billing and Invoices</option>
                    <option value="3" {{ @$model->team_id == 3 ? 'selected' : '' }}>Refund and Dispute</option>
                    <option value="4" {{ @$model->team_id == 4 ? 'selected' : '' }}>General Questions</option>
                </select>
        </div>
        </div>

        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" placeholder="Title" name="title" required="required"
                        aria-label="Name" value="{{ @$model->title }}" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Description <span class="text-danger">*</span></label>
                 <textarea required class="form-control" name="body" id="summernote" rows="5" placeholder="Provide a detailed description of the issue." required>{{ @$model->body }}</textarea>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <button type="submit" class="btn btn-primary me-2 pjax">Submit</button>
            <a class="btn btn-dark router text-white" data-bs-dismiss="modal" aria-label="Close">Cancel</button></a>
        </div>
    </div>
    <div id="form-message" class="mb-3" style="display:none;"></div>
</form>

@push('scripts')
<script type="text/javascript">
    documentReady(function() {
        $('.ajax-form').validate({
            rules: {
                title: {
                    required: true,
                },
                body: {
                     required: true,
                },
            },
            messages: {
                 title: {
                     required: "Please enter title",
                },
                body: {
                     required: "Please enter body",
                 },
            },
            submitHandler: function(form) {
                app.ajaxFileForm(form, function(response) {
                    if (response.status == 1) {
                        app.showMessage(response.message, 'success');
                        app.hideModal();
                        setTimeout(function() {
                            app.nextAction(response);
                        }, 500);
                    } else if (response.message) {
                        $('#form-message').text(response.message).removeClass('text-success').addClass('text-danger').show();
                    }
                });
            },
            errorPlacement: function(error, element) {
            error.insertAfter(element.closest('.mb-3'));
        },
        })
    });
</script>
@endpush
