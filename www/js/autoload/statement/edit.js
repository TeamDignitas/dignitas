$(function() {

  const DELAY = 1000;

  var stem = null; // stem source

  function init() {
    stem = $('#stem').detach().removeAttr('hidden');

    $('#addSourceButton').click(addSource);
    $('#sourceContainer').on('click', '.deleteSourceButton', deleteSource);

    Sortable.create(sourceContainer, {
      handle: '.icon-move',
	    animation: 150,
    });

    initTypingTimer();

    initSelect2('#fieldEntityId', URL_PREFIX + 'ajax/load-entities', {
      ajax: {
        url: URL_PREFIX + 'ajax/search-entities',
      },
      minimumInputLength: 2,
    });
  }

  function initTypingTimer() {
    var typingTimer;

    // start the timer on keyup
    $('#fieldContext').on('keyup', function() {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping, DELAY);
    });

    // clear the timer on keydown
    $('#fieldContext').on('keydown', function () {
      clearTimeout(typingTimer);
    });
  }

  // runs after DELAY milliseconds from the last keypress
  function doneTyping () {
    var raw = $('#fieldContext').val();
    $('#markdownPreview').html(marked(raw));
  }

  function addSource() {
    var t = stem.clone(true).appendTo('#sourceContainer');
  }

  function deleteSource() {
    $(this).closest('tr').remove();
  }

  init();

});
