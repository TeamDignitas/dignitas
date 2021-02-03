$(function() {
  function init() {
    var mimeType = $('#field-contents').data('mime');
    var textarea = document.getElementById('field-contents');
    CodeMirror.fromTextArea(textarea, {
      lineWrapping: true,
      mode: mimeType,
      extraKeys: {
        Home: 'goLineLeft',
        End: 'goLineRight',
      },
    });
  }

  init();

});
