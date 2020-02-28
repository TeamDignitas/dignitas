$(function() {

  var commentForm = null;

  function init() {
    commentForm = $('#commentForm').detach().removeAttr('id');
    $('.addCommentLink').click(addCommentForm);
    $('.deleteCommentLink').click(deleteComment);
    $('body').on('click', '.commentSaveButton', saveComment);
    $('body').on('click', '.commentCancelButton', hideCommentForm);
    $('body').on('keyup paste', 'textarea[name="contents"]', showRemainingChars);
  }

  function addCommentForm() {
    // clone the form and populate some fields
    var c = commentForm.clone(true);
    c.find('input[name="objectType"]').val($(this).data('objectType'));
    c.find('input[name="objectId"]').val($(this).data('objectId'));

    // show it beneath the answer actions
    var anchor = $(this).closest('.list-inline');
    c.insertAfter(anchor);

    // hide the link and save it in the form itself
    $(this).closest('li').hide().appendTo(c);

    return false;
  }

  function hideCommentForm() {
    var f = $(this).closest('form');

    // put the "add comment" link back in the <ul>
    var link = f.find('li');
    link.show().appendTo(f.prev('.list-inline'));

    // remove the form
    f.remove();

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

  function deleteComment() {
    var msg = $(this).data('confirmMsg');
    if (!confirm(msg)) {
      return false;
    }

    $('body').addClass('waiting');

    var comment = $(this).closest('.comment');
    var commentId = $(this).data('commentId');
    $.get(URL_PREFIX + 'ajax/delete-comment/' + commentId)
      .done(function(successMsg) {

        comment.slideToggle();

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
