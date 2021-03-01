$(function() {

  var initialized = false;

  function init() {
    var cm = $('.CodeMirror')[0].CodeMirror;
    cm.on('focus', initAnswerResources);
    $('.answer-resources-link').click(toggleModal);
    $('#checkboxAnswerResources').change(toggleCheckbox);
    $('#answer-resources').on('shown.bs.modal', identifyBackdrop);
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

  // When this modal is shown, it should be the only one. So we take this
  // chance to give an ID to its backdrop so we can style it.
  function identifyBackdrop() {
    $('.modal-backdrop').first().attr('id', 'answer-resources-backdrop');
  }

  init();

});
