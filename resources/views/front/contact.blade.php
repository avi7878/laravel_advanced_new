@extends('layouts.main')
@section('title')
Contact
@endsection
@section('content')

<h4 class="fw-bold py-3 mb-4">Contact</h4>
<div class="card card-default color-palette-box">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 ">
                {{ view('common/message_alert') }}
                <form class="ajax-contact-form" id="form" method="post" action="{{route('contact-process')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="body" for="basic-icon-default-fullname">Name</label>
                                <div class="">
                                    <input type="text" class="form-control simple" placeholder="Name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="body" for="basic-icon-default-fullname">Email</label>
                                <div class="">
                                    <input type="email" class="form-control simple" placeholder="Email" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="body" for="basic-icon-default-fullname">Subject</label>
                                <div class="">
                                    <input type="text" class="form-control simple" placeholder="Subject" name="subject" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="body" for="basic-icon-default-fullname">Message</label>
                                <div class="">
                                    <textarea name="message" placeholder="Message" class="form-control simple" required></textarea><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            {{view('common/recaptcha')}}
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary text-white" style="background-color:#685dd8;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Bootstrap Tables-->
@endsection
@push('scripts')
<script type="text/javascript">
    documentReady(function() {
        $('.ajax-contact-form').validate({
            submitHandler: function(form) {
                app.ajaxForm(form);
            }
        })
    });
</script>
@endpush