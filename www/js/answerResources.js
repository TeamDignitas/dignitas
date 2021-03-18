$(function() {

  var initialized = false;

  function init() {
    var cm = $('.CodeMirror')[0].CodeMirror;
    cm.on('focus', initAnswerResources);
    $('.answer-resources-link').click(toggleModal);
    $('#checkboxAnswerResources').change(toggleCheckbox);
    $('#answer-resources').on('show.bs.modal hidden.bs.modal', styleBody);
  }

  function initAnswerResources(evt) {
    // only do this the first time we focus the CodeMirror field
    if (!initialized) {
      initialized = true;
      if ($("#checkboxAnswerResources").is(':checked')) {
        $('#answer-resources').modal('show');
      }
    }
  }

  function toggleModal(evt) {
    initialized = true;
    $('#answer-resources').modal('toggle');
  }

  function toggleCheckbox() {
    $('body').addClass('waiting');
    $.get(URL_PREFIX + 'ajax/toggle-answer-resources')
      .always(function() {
        $('body').removeClass('waiting');
      });
  }

  // Add a class to the <body> as soon as the modal opens ('show') and last
  // thing after it closes ('hidden'). We will use the class to control the
  // backdrop.
  function styleBody() {
    $('body').toggleClass('has-answer-resources');
  }

  init();

});
