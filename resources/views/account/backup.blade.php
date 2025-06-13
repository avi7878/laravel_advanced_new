<div class="container mt-4">
    <div class="card">
        <div class="card-header text-white">
            <h5 class="mb-0">Backup Codes</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('backup-codes.regenerate') }}">
                @csrf

                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="backup_code" name="backup_code" placeholder="Backup Code Is Not Set!" value="{{$backupCode}}">
                </div>

                <div class="flex items-center justify-end gap-4 p-4 border-t border-t-border-primary">
                    <button type="button" class="btn btn-outline-secondary mb-4 mx-3" data-bs-dismiss="modal">Cancel</button>

                    <a onclick="app.showModalView('get-qr-modal')" for="upload" class="btn btn-primary me-3 mb-4 text-white pjax" tabindex="0">
                        <span class="d-none d-sm-block">Regenerate
                        </span>
                        <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>