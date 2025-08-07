<style>
    .ck.ck-content.ck-editor__editable.ck-rounded-corners.ck-editor__editable_inline.ck-blurred p {
        color: #000 !important;
    }

    .ck.ck-content.ck-editor__editable.ck-rounded-corners.ck-editor__editable_inline.ck-focused p {
        color: #000 !important;
    }

    .file-preview .file-preview-item img {
        width: 434px;
        max-width: 100%;
        height: 100%;
    }

    .waves-light {
        margin-top: 35px;
    }

    .file-preview-item {
        position: relative;
    }

    .file-preview-item button {
        position: absolute;
        top: -13px;
        right: 0px;
        border-radius: 37px;
        width: 25px;
        height: 25px;
        background: black;
    }

    .file-preview-item button .fa-xmark {
        color: white;

    }
</style>
<form action="admin/blog/save" data-redirect="admin/blog/index" class="ajax-form" method="post" autocomplete="off"
    id="blogForm" onsubmit="" enctype="multipart/form-data">
    @csrf
    @if (@$blog->id)
        <input type="hidden" name="id" value="{{ $blog->id }}">
        <input type="hidden" name="old_image" value="{{ $blog->image }}">
    @endif
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="category">Blog category <span class="text-danger">*</span></label>
                    <div class="input-group input-group-merge">
                        <select class="form-select" name="category" id="category">
                            @foreach ($bcategory as $val)
                                <option value="{{ $val->id }}" <?php if (@$val->id == @$blog->blog_category_id) {
                                    echo ' selected';
                                } ?>>{{ $val->category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ @$blog->title ? $blog->title : '' }}"
                            id="title" placeholder="Enter title" name="title" autocomplete="title" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="mb-3">
            <label class="form-label" for="basic-icon-default-password">Tags</label>
            <div class="input-group input-group-merge"> 
                <input type="tag" class="form-control" value="{{ @$blog->tags ? $blog->tags : '' }}" id="tags"
                    placeholder="Enter tag" name="tags" />
            </div>
        </div>
    </div>
     <div class="col-12">
            <?php if (!empty($blog->image)) { ?>
            <img src="{{ $general->getFileUrl($blog->image, 'upload/blog/') }}" style="width:100px;height:100px"
                class="img-fluid" id="image"><br>
            <?php } else { ?>
            <img src="{{ $general->getNoFile() }}" class="img-fluid" style="width:100px;height:100px" id="image"><br>
            <?php  } ?>
                <div class="form-group">
                    <label>Image</label><br><br>
             <div class="custom-file">
                <input type="file" class="form-control custom-file-input" accept="image/*" name="image" onchange="previewImage(this,'#image')">
            </div>
     </div>

      <div class="col-12">
        <div class="mb-3">
            <div class="form-group noroute">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea id="summernote" class="form-control"  name="description" placeholder="Enter Description">{{ @$blog->description ? $blog->description : '' }}</textarea>
                <label id="summernote-error" class="error" for="summernote"></label>
            </div>
        </div>
    </div>
    
     <div class="col-12">
        <div class="mb-3">
        <div class="form-group">
            <label for="description" class="form-label">Gallery</label>
            <div id="filedropbox"></div>
        </div>
        </div>
     </div>

    <div class="col-md-4">
        <div class="form-group">
            <button type="submit" class="btn btn-primary me-2">Submit</button>
            <a class="btn btn-dark router pjax" href="admin/blog/index">Back</button></a>
        </div>
    </div>

</form>

@push('scripts')
    <script type="text/javascript">
    let hasExistingImage = "{{ @$blog->image ? 'true' : 'false' }}" === "true";

        var editorObj = false;
        documentReady(function() {
            jQuery.validator.addMethod("alphaOnly", function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Please enter only alphabetic characters");

            app.addCSS(['https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css']);
            app.loadScript('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js',
                function() {
                    initEditor($('#summernote'));
                });
                function initEditor($element) {
                    $element.summernote({
                        height: 200,
                        callbacks: {
                            onChange: function(contents, $editable) {
                                $element.valid(); 
                            }
                        }
                    });
                }

            fileDropBox.init('#filedropbox', <?= isset($oldFiles) && $oldFiles ? $oldFiles : '[]' ?>);
            $('.ajax-form').validate({
                 ignore: "",
                rules: {
                    title: {
                        required: true,
                        alphaOnly: true,
                    },
                    description: {
                         required: function () {
                                return $('#summernote').summernote('isEmpty');
                        }
                    },
                     image: {
                            required: function(element) {
                                return $('#formFile').val() === '' && !hasExistingImage;
                            }
                        },
                },
                messages: {
                    title: {
                        required: "Please enter the title",
                    },
                    description: {
                         required: "Please enter Description is required."
                    },
                    image: {
                        required: "Please upload an image."
                    },
                },
                 errorPlacement: function (error, element) {
                    if (element.attr('id') === 'summernote') {
                        $('#summernote-error').html(error);
                    } else {
                        error.insertAfter(element.closest('.mb-3'));
                    }
                     if (element.attr("name") === "image") {
                        error.insertAfter("#image-error"); 
                    } else {
                        error.insertAfter(element.closest('.mb-3'));
                    }
                },
                submitHandler: function(form) {
                    $('#summernote').val($('#summernote').summernote('code'));
                    var postData = new FormData(form);
                    $.each(fileDropBox.files, function(index, file) {
                        postData.append('gallery[]', file);
                    })
                    app.ajaxFileRequest($(form).attr("action"), postData);
                },
            })
        });
    </script>
    <script>
        var tagbox = new Tagify(document.querySelector('#tags'));
    </script>
@endpush
