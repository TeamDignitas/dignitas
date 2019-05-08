/********** Confirmations before discarding pending changes **********/
$(function() {
  var beforeUnloadHandlerAttached = false;

  $('.deleteButton').click(function() {
    var msg = $(this).data('confirm');
    return confirm(msg);
  });

  // ask for confirmation before navigating away from a modified field...
  $('.hasUnloadWarning').on('change input', function() {
    if (!beforeUnloadHandlerAttached) {
      beforeUnloadHandlerAttached = true;
      $(window).on('beforeunload', function() {
        // this is ignored in most browsers
        return 'Are you sure you want to leave?';
      });
    }
  });

  // ...except when actually submitting the form
  $('.hasUnloadWarning').closest('form').submit(function() {
    $(window).unbind('beforeunload');
  });

});

/********** Vote submissions **********/
$(function() {
  $('.voteButton').click(submitVote);

  function submitVote() {
    var btn = $(this);
    $('body').addClass('waiting');
    $.get(URL_PREFIX + 'ajax/save-vote', {
      value: btn.data('value'),
      type: btn.data('type'),
      objectId: btn.data('objectId'),
    }).done(function(newScore) {

      // update the score
      btn.closest('.scoreAndVote').find('.score').text(newScore);

      // enable the opposite button
      btn.siblings('.voted').removeClass('voted');

      // toggle this button
      btn.toggleClass('voted');

    }).fail(function(errorMsg) {
      if (errorMsg.responseJSON) {
        alert(errorMsg.responseJSON);
      }
    }).always(function() {
      $('body').removeClass('waiting');
    });
  }

});
