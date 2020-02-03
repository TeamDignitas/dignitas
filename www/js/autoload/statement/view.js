$(function() {

  var commentForm = null;

  function init() {
    initSimpleMde('fieldContents');
    commentForm = $('#commentForm').detach();
    $('.addCommentLink').click(addCommentForm);
    $('body').on('click', '.commentSaveButton', saveComment);
  }

  function addCommentForm() {
    var c = commentForm.clone(true);
    c.find('input[name="objectType"]').val($(this).data('objectType'));
    c.find('input[name="objectId"]').val($(this).data('objectId'));
    c.insertAfter($(this));
    $(this).remove();
    return false;
  }

  function saveComment() {
    $('body').addClass('waiting');

    var form = $(this).closest('form');
    $.post(
      URL_PREFIX + 'ajax/save-comment',
      form.serialize()
    ).done(function(successMsg, textStatus, xhr) {

      window.location.reload();

    }).fail(function(errorMsg) {

      if (errorMsg.responseJSON) {
        alert(errorMsg.responseJSON);
      }

    }).always(function() {

      $('body').removeClass('waiting');

    });

    return false;
  }

  init();

});
