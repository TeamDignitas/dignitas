$(function() {

  var commentForm = null;

  function init() {
    initSimpleMde('fieldContents');
    commentForm = $('#commentForm').detach().removeAttr('id');
    $('.addCommentLink').click(addCommentForm);
    $('body').on('click', '.commentSaveButton', saveComment);
    $('body').on('keyup paste', 'textarea[name="contents"]', showRemainingChars);
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

  function showRemainingChars() {
    // We trust the browser to obey maxlength. This is safe because we also
    // have a backend check.
    var l = $(this).val().length;
    var max = $(this).attr('maxlength');

    $(this).closest('form').find('.charsRemaining').text(max - l);
  }

  init();

});
