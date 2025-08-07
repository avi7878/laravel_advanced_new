<style>
    .select2-container {
    z-index: 99999;
}
</style>
<div class="modal-header">

    <h1 class="modal-title fs-4" style="color:#5A6DED;">Create Group</h1>
    <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
       <img src="theme/assets/images/modal_close.svg" alt="" class="close-images">
    </button>
</div>
<form class="upload-box" action="{{ route('group/create') }}" onsubmit="event.preventDefault();app.ajaxFileForm(this)" enctype="multipart/form-data">
    @csrf
    <div class="modal-body chat-modal">
        <div class="chat-select-user">
            <label>User</label>
          
                  
            <select name="user_ids[]" class="form-control" id="multiple" multiple>
            <!-- <option value="1">John Doe (john@example.com)</option>
            <option value="2">Jane Smith (jane@example.com)</option> -->
                <?php if ($userList) {
                    
                    foreach ($userList as $usr) { ?>
                        <option value="{{ $usr->id }}">{{ $usr->first_name.' ('.$usr->email.')'}}</option>
                <?php }
                   } ?>
            </select>

        </div>

        <label>Title</label>
        <input type="text" name="title" id="title" required placeholder="Title" class="form-control">


        <div class="new admins_check">
            <div class="form-group">
                <div class="col-md-12 text-center">
                    <div class="image-crop-box" style="max-width:200%;">
                        <img id="image-crop">
                    </div>
                    <br>
                    <div class="image-crop-action" style="display:none;max-width:100%;">
                        <button type="button" onclick="imageCrop.rotateLeft()" class="btn btn-default r-left">Rotate Left</button>
                        <button type="button" onclick="imageCrop.rotateRight()" class="btn btn-default r-right">Rotate Right</button>
                        <button type="button" class="btn btn-primary" onclick="imageCrop.showImage('previewCrop')" class="submit">Select</button>
                    </div>
                    <img id="previewCrop">
                    <input type="hidden" name="image" id="croppedImage">
                </div>

            </div>
        </div>


        <div class="form-group img-upld" tooltip="Upload only Image" flow="down">
            <label for="imageFile" class="js-labelFile">
                <input type='file' class="input-file" onchange="imageCrop.setCropFile(this.files[0])" name="image" accept="image/*">
                <a type="button" onclick="$(this).prev().click()">Upload Image</a>
                <span class="js-fileName"></span>
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-secondary" id="common-modal-cancel" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary ">Submit</button>
    </div>
</form>

<script>
    var imageCrop = false;
    documentReady(function() {
        imageCrop = new ImageCrop();
        imageCrop.init('image-crop', '');

        $('#multiple').select2({
            placeholder: 'Select an user'

        });

    })
</script>
