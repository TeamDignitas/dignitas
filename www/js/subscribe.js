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
      showConfirmModal(successMsg);

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
      showConfirmModal(successMsg);

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

  function showConfirmModal(msg) {
    $('#modal-subscribe-confirm .modal-body').html(msg);
    $('#modal-subscribe-confirm').modal('show');

    setTimeout(function() {
      $('#modal-subscribe-confirm').modal('hide');
    }, 1500);
  }

  $('a.subscribe').click(submitSubscribe);
  $('a.unsubscribe').click(submitUnsubscribe);

});
