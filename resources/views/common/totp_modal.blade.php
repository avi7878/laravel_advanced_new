  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">

      <div class="modal-header text-white text-center">
        <h5 class="modal-title w-100 mb-0" id="qrModalLabel">Set up Authenticator App</h5>
      </div>

      <div class="px-4 pt-3">
        <p class="text-muted small text-start">
          In the Google Authenticator app, tap the <strong>+</strong> and choose <strong>Scan a QR code</strong>.
        </p>
      </div>

      <div class="modal-body text-center">
        <div class="mb-3">
          {!! $qrCode !!}
        </div>

        <p class="text-muted fw-semibold bg-white d-inline-block px-2 position-relative z-1">
          OR enter the code manually
        </p>
        <p id="secretKey" class="fw-semibold text-primary mt-2">

        </p>

        <div class="input-group">
          <input type="text" class="form-control text-center" id="secretKeyInput"
                 name="secretKey" value="{{ $secretKey }}">
        
          <button class="btn btn-outline-secondary" type="button" onclick="copySecretKey()" title="Copy to clipboard">
            <i class="fas fa-copy"></i>
          </button>
        </div>
             <div class="modal-footer justify-content-between px-4 pb-4 border-0 pt-5">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
          
          <button 
            type="button" 
            class="btn btn-primary me-3 mb-4 text-white" 
            tabindex="0" 
            onclick="app.showModalView('otp.verify?secretKey={{ $secretKey }}')" >
            <span class="d-none d-sm-block">Next</span>
            <i class="icon-base bx bx-upload d-block d-sm-none"></i>
          </button>
        </div>


    </div>
  </div>
  
  <script>
  function copySecretKey() {
    const input = document.getElementById('secretKeyInput');
    const secretKey = input.value;

    navigator.clipboard.writeText(secretKey).then(() => {
      $.ajax({
        url: '/copy.secret.key', 
        method: 'POST',
        data: {
          secretKey: secretKey,
          _token: '{{ csrf_token() }}' 
        }
      });
    }).catch((err) => {
      console.error('Clipboard error:', err);
    });
  }
</script>
