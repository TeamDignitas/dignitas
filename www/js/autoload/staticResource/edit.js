$(function() {
  function init() {
    var mimeType = $('#field-contents').data('mime');
    var textarea = document.getElementById('field-contents');
    CodeMirror.fromTextArea(textarea, { mode: mimeType });
  }

  init();

});
