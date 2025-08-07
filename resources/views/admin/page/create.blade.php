@extends('admin.layouts.main')
@section('title')
Page Create
@endsection
@section('content')

<div class="breadcrumb-box">
    <h4 class="fw-bold py-3 mb-4">Page</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="admin/dashboard" >Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="admin/page" class="pjax">Page</a>
            </li>
            <li class="breadcrumb-item active">Page Create</li>
        </ol>
    </nav>
</div>
<div class="card card-default color-palette-box">
    <div class="card-header justify-content-between">
        <h4 class="align-middle mb-0">Page Create</h4>
    </div>
    <div class="card-body">
        <form id="ajax-form" method="post" action="admin/page/create">
            @csrf
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" >Title <span class="text-danger">*</span></label>
                    <div class="input-group input-group-merge">
                        <input type="text" class="form-control" id="title" placeholder="Title" name="title" aria-label="Name" value="" />
                    </div>
                </div>
            </div>
           
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a class="pjax" href="admin/pages"><button type="button" class="btn btn-primary">Back</button></a>
        </form>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
documentReady(function() {
    $('#ajax-form').validate({
        rules: {
                    title: {
                        required: true,
                        minlength: 2
                    },
                   
                },
                messages: {
                    title: {
                        required: "Please enter the title",
                        minlength: "Please enter at least 2 characters"
                    }, 
                   
                },
        submitHandler: function(form) {
                    app.ajaxForm(form);
                }
    })
});
</script>
@endpush
@endsection
