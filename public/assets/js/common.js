documentReady(function() {
  // Password toggle visibility for all pages with debug logs
  $(document).on('click', '.form-password-toggle .input-group-text', function() {
    console.log('Password toggle clicked');
    var $icon = $(this).find('i');
    var $input = $(this).closest('.input-group').find('input[type="password"], input[type="text"]').first();
    console.log('Input found:', $input);
    if ($input.length === 0) {
      console.warn('No input found for password toggle');
      return;
    }
    if ($input.attr('type') === 'password') {
      $input.attr('type', 'text');
      $icon.removeClass('bx-hide').addClass('bx-show');
      console.log('Password shown');
    } else {
      $input.attr('type', 'password');
      $icon.removeClass('bx-show').addClass('bx-hide');
      console.log('Password hidden');
    }
  });
});


// $(function () {
//   $('#email, #password, #password_confirm').on('input', function () {
//     const $el = $(this), isEmpty = !$el.val().trim();
//     const $inputGroup = $el.closest('.input-group');

//     $el.toggleClass('error-border', isEmpty);
//     $inputGroup.find('.input-group-text').toggleClass('error-border', isEmpty);
//     $('#' + this.id + '-error').toggle(isEmpty);
//   });
// });



