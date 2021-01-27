$(function() {

  var sideSheet = $('#answer-resources');

  function init() {
    var hash = window.location.hash;
    if (hash) {
      $(hash).addClass('highlighted');
    }

    $('#answer-resources-minimize').click(minimizeAnswerResources);
    $('#answer-resources-maximize').click(maximizeAnswerResources);
    $('#answer-edit').focusin(initAnswerResources);
    $('#checkboxAnswerResources').change(toggleCheckbox);
  }

  function initAnswerResources() {
    // only do this the first time we focus the form
    if (!sideSheet.hasClass('minimized') && !sideSheet.hasClass('maximized')) {
      if ($("#checkboxAnswerResources").is(':checked')) {
        maximizeAnswerResources();
      } else {
        minimizeAnswerResources();
      }
    }
  }

  function minimizeAnswerResources() {
    sideSheet.removeClass('maximized').addClass('minimized');
  }

  function maximizeAnswerResources() {
    sideSheet.removeClass('minimized').addClass('maximized');
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
