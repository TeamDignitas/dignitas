$(function() {

  function enableDoneButton() {
    $('#doneButton').prop('disabled', false);
  }

  $('.voteButton').click(enableDoneButton);
  $('#flagButton').click(enableDoneButton);
  $('#flagModal').submit(enableDoneButton);

});
