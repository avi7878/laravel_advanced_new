<div class="modal-dialog modal-dialog-centered" id="verifyOtpModal">
    <div class="modal-content border-0 shadow">

        <div class="modal-header text-white text-center">
            <h5 class="modal-title w-100 mb-0" id="verifyOtpModalLabel">Verify OTP</h5>
        </div>

        <div class="modal-body">
            <p class="text-muted text-center">
                Enter the 6-digit code from your authenticator app.
            </p>

            <form id="otpForm" method="POST" action="{{ route('otp.confirm') }}">
                <input type="hidden" name="secretKey" value="{{$secretKey}}">
                <input type="hidden" name="id" value="{{$id}}">
                @csrf
                <div class="mb-3">
                    <label for="otp_code" class="form-label">OTP Code <span class="text-danger">*</span></label>
                    <input type="text" name="otp" id="otp_code" class="form-control text-center" maxlength="6" required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Verify</button>
                </div>
            </form>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#otpForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Confirm OTP',
            text: 'Do you want to verify this OTP code?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post($(this).attr('action'), $(this).serialize(), function(response) {

                    if (response.status === 1) {
                        Swal.fire('Success', response.message, 'success');
                        $('#verifyOtpModal').modal('hide');
                        if (response.next === 'refresh') {
                            location.reload();
                        }
                    } else {
                        Swal.fire('Failed', response.message || 'Verification failed.',
                            'error');
                    }
                });
            }
        });
    });
});
</script>