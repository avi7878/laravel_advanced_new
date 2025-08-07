
<div class="modal-header">
    <h4 class="modal-title">Note Update</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="col-md-12">
        {{ view('note/_form',['model'=>$model])}}
</div>
<div class="modal-footer justify-content-between">
   