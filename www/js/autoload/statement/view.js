$(function() {

  var sideSheet = $('#answer-resources');
  var initialized = false;

  function init() {
    var hash = window.location.hash;
    if (hash) {
      $(hash).addClass('highlighted');
    }

    var cm = $('.CodeMirror')[0].CodeMirror;
    cm.on('focus', initAnswerResources);
    $('.answer-resources-link').click(toggleModal);
    $('#checkboxAnswerResources').change(toggleCheckbox);
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

  init();

});
