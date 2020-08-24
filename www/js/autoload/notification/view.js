$(function() {

  function init() {
    $('.notification-unsubscribe').click(notificationUnsubscribe);
  }

  function notificationUnsubscribe(e) {
    var link = $(this);
    var notificationId = $(this).data('notificationId');

    $.get(URL_PREFIX + 'ajax/notification-unsubscribe', {
      id: notificationId,
    }).done(function() {
      link.off('click');
      link.addClass('disabled');
    });

    return false;
  }

  init();

});
