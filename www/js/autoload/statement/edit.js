$(function() {

  const DELAY = 1000;

  function init() {
    var typingTimer;

    // start the timer on keyup
    $('#fieldContents').on('keyup', function() {
      console.log('timer!');
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping, DELAY);
    });

    // clear the timer on keydown
    $('#fieldContents').on('keydown', function () {
      clearTimeout(typingTimer);
    });
  }

  // runs after DELAY milliseconds from the last keypress
  function doneTyping () {
    var raw = $('#fieldContents').val();
    $('#markdownPreview').html(marked(raw));
  }

  init();

});
