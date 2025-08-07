<div class="modal-header">
    <h4 class="modal-title">Note View</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     
</div>


<div class="modal-body">
   <div class="col-lg-12" id="ajax-container">
    <div class="card-header justify-content-between" >
        <div class="d-flex justify-content-end mb-4">
            <a class="btn btn-dark" href="note" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
            <a class="btn btn-primary ms-2 pjax"  href="javascript:void(0);" onclick="app.showModalView('note/update?id={{ $_GET['id'] }}')">Edit</a>
        </div>
         <div class="card-body p-0" id="ajax-content">
                <table class="table table-striped-columns">
                    <tbody>
                        <tr>
                            <th>Title</th>
                            <td>{{ $model->title }}</td>
                        <tr>
                            <th>Note</th>
                            <td>{!! $model->description !!}</td>
                        <tr>
                            <th>Created At</th>
                            <td>{{ date('Y-m-d h:i A', strtotime($model->created_at)); }}</td>
        
                    </tbody>
            </table>
     </div>
  </div>
   
</div>
<div class="modal-footer justify-content-between">
   
</div>

