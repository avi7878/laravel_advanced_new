@extends('layouts.blank')
@section('title')
New ticket
@endsection
@section('content')
<?php $user = \Illuminate\Support\Facades\Auth::user(); ?>
<style>
    button.btn.mt-2.redirect-text-ticktes a {
        color: #fff;
    }
</style>
<div class="bg-default-2 pt-20 pt-lg-22 pb-lg-27 pb-8 dashboard">
    <div class="container">
        <div class="main-content-part container-fluid">
            <div class="row">
                <div class="main-content col-md-12">
                    <div class="container">
                        <span class="error-msg"></span>
                        <h3 class="font-size-4 mb-0 fw-bold pt-4">New Ticket</h3>
                        <div class="bg-white shadow-8 pt-10 rounded pb-12 px-11 mt-5">
                            <div class="card-body">
                                <div class="col-md-12"></div>
                            <form action="{{ url('support/create') }}" method="post" class="ajax-form">
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
                                                <option value="1">Technical Support</option>
                                                <option value="2">Billing and Invoices</option>
                                                <option value="3">Refund and Dispute</option>
                                                <option value="4">General Questions</option>
                                            </select>
                                    </div>
                                    </div>
                            
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="basic-icon-default-fullname">Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="title" placeholder="Title" name="title" required="required"
                                                    aria-label="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="basic-icon-default-fullname">Description <span class="text-danger">*</span></label>
                                             <textarea required class="form-control" name="body" id="summernote" rows="5" placeholder="Description" required></textarea>
                                        </div>
                            
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary me-2 pjax">Submit</button>
                                        <a href="support" class="btn btn-dark router text-white"  aria-label="Close">Cancel</button></a>
                                    </div>
                                </div>
                                <div id="form-message" class="mb-3" style="display:none;"></div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    documentReady(function() {
        $('.ajax-form').validate({
            submitHandler: function(form) {
                app.ajaxFileForm(form);
            },
            
              rules: {
                team_id: {
                    required: true
                },
                title: {
                    required: true
                },
                body: {
                    required:true
                }
            },
            messages: {
                 team_id: {
                    required: "Please enter the department."
                },
                title: {
                    required: "Please enter the title."
                },
                body: {
                    required: "Please enter the description."
                }
            }
        })
    });
</script>
@endpush
@endsection