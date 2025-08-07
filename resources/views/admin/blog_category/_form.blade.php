 <style>
     .star {
         color: red;
     }
 </style>
 <form action="{{ route('admin/blog_category/save') }}" onsubmit="ajaxForm(event,this)" data-redirect="blog_category/index"
     method="post" class="ajax-form">
     @csrf
     <input type="hidden" name="id" value="{{ @$_GET['id'] }}">

     <div class="col-md-6">
         <div class="mb-3">
             <label class="form-label" for="basic-icon-default-password">Category <span class="star">*</span></label>
             <div class="input-group input-group-merge">
                 <input type="text" class="form-control" value="{{ @$blogcat->category ? $blogcat->category : '' }}"
                     id="title" placeholder="Enter category" name="category" />
             </div>
         </div>
     </div>
     <div class="col-md-6">
         <div class="mb-3">
             <label class="form-label" for="category">Status</label>
             <div class="input-group input-group-merge">
                 <select name="status" id="status" class="nice-select h-100 arrow-3 font-size-4 form-select w-100">
                     <option value="1" <?php if (@$blogcat->status === 1) {
                         echo 'selected';
                     } ?>>Active</option>
                     <option value="0" <?php if (@$blogcat->status === 0) {
                         echo 'selected';
                     } ?>>Not Approved/ Inactive</option>
                 </select>
             </div>
         </div>
     </div>

     <div class="col-md-4">
         <div class="form-group">
             <button type="submit" class="btn btn-primary me-2">Submit</button>

             <a class="btn btn-dark router pjax" href="admin/blog_category/index">Back</button></a>
         </div>
     </div>

 </form>

 @push('scripts')
     <script type="text/javascript">
         documentReady(function() {
             jQuery.validator.addMethod("alphaOnly", function(value, element) {
                 return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
             }, "Please enter only alphabetic characters");
             $('.ajax-form').validate({
                 rules: {
                     category: {
                         required: true,
                         alphaOnly: true,
                     },
                 },
                 messages: {
                     category: {
                         required: "Please enter the category",
                     },
                 },
                 submitHandler: function(form) {
                     app.ajaxForm(form);
                 },
                 errorPlacement: function(error, element) {
                     error.insertAfter(element.closest('.mb-3'));
                 },
             })
         });
     </script>
 @endpush
