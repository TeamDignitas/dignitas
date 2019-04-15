$(function() {
  var beforeUnloadHandlerAttached = false;

  $('.deleteButton').click(function() {
    var msg = $(this).data('confirm');
    return confirm(msg);
  });

  // ask for confirmation before navigating away from a modified field...
  $('.hasUnloadWarning').on('change input', function() {
    if (!beforeUnloadHandlerAttached) {
      beforeUnloadHandlerAttached = true;
      $(window).on('beforeunload', function() {
        // this is ignored in most browsers
        return 'Are you sure you want to leave?';
      });
    }
  });

  // ...except when actually submitting the form
  $('.hasUnloadWarning').closest('form').submit(function() {
    $(window).unbind('beforeunload');
  });

});
