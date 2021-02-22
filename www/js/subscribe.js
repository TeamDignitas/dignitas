// This module makes Ajax requests when the user clicks the
// subscribe/unsubscribe buttons under statements, answers and entities.
$(function() {

  function submitSubscribe() {
    subscribeLink = $(this);
    unsubscribeLink = $(subscribeLink.data('unsubscribeLink'));
    objectType = subscribeLink.data('objectType');
    objectId = subscribeLink.data('objectId');

    $('body').addClass('waiting');

    $.post(URL_PREFIX + 'ajax/subscribe', {
      objectType: objectType,
      objectId: objectId,

    }).done(function(successMsg, textStatus, xhr) {
      subscribeLink.prop('hidden', true);
      unsubscribeLink.prop('hidden', false);
      showSnackbar(successMsg, subscribeLink);

    }).fail(function(errorMsg) {

      if (errorMsg.responseJSON) {
        alert(errorMsg.responseJSON);
      }

    }).always(function() {

      $('body').removeClass('waiting');

    });

    return false;
  }

  function submitUnsubscribe() {
    unsubscribeLink = $(this);
    subscribeLink = $(unsubscribeLink.data('subscribeLink'));
    objectType = unsubscribeLink.data('objectType');
    objectId = unsubscribeLink.data('objectId');

    $('body').addClass('waiting');

    $.post(URL_PREFIX + 'ajax/unsubscribe', {
      objectType: objectType,
      objectId: objectId,

    }).done(function(successMsg, textStatus, xhr) {

      // swap link visibility
      subscribeLink.prop('hidden', false);
      unsubscribeLink.prop('hidden', true);
      showSnackbar(successMsg, unsubscribeLink);

    }).fail(function(errorMsg) {

      // this shouldn't happen, but the request may occasionally time out
      if (errorMsg.responseJSON) {
        alert(errorMsg.responseJSON);
      }

    }).always(function() {

      $('body').removeClass('waiting');

    });

    return false;
  }

  /**
   * @param link Link that caused the snackbar to be displayed.
   **/
  function showSnackbar(msg, link) {
    link.closest('.dropdown-menu').prev().dropdown('toggle');
    snackbar(msg);
  }

  $('a.subscribe').click(submitSubscribe);
  $('a.unsubscribe').click(submitUnsubscribe);

});
