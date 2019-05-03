/* Custom code built on top of marked.js */
const PREVIEW_DELAY = 1000;

// make the preview element update whenever the input element changes
function setMarkdownPreview(input, preview) {
  var typingTimer;

  // start the timer on keyup
  input.on('keyup', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function() {
      var raw = input.val();
      preview.html(marked(raw));
    }, PREVIEW_DELAY);
  });

  // clear the timer on keydown
  input.on('keydown', function () {
    clearTimeout(typingTimer);
  });
}
