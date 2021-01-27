$(function() {

  function init() {
    var hash = window.location.hash;
    if (hash) {
      $(hash).addClass('highlighted');
    }

    $('#answer-resources-minimize').click(minimizeAnswerResources);
    $('#answer-resources-maximize').click(maximizeAnswerResources);
    $('#answer-edit').focusin(initAnswerResources);
  }

  function initAnswerResources() {
    // only maximize it the first time we focus the form
    if (!$('#answer-resources').hasClass('minimized')) {
      maximizeAnswerResources();
    }
  }

  function minimizeAnswerResources() {
    $('#answer-resources')
      .removeClass('maximized')
      .addClass('minimized');
  }

  function maximizeAnswerResources() {
    console.log('hello');
    $('#answer-resources')
      .removeClass('minimized')
      .addClass('maximized');
  }

  init();

});
