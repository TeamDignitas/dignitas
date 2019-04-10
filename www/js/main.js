$(function() {
  $('.deleteButton').click(function() {
    var msg = $(this).data('confirm');
    return confirm(msg);
  });
});
