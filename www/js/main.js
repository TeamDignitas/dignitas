/*************************** vanishing alerts ***************************/
$(function() {
  setTimeout(function() {
    $('#flash-messages > .alert').fadeTo(1000, 0, function() {
      $(this).hide();
    });
  }, 5000);
});


/*************************** nav sidebar slide ***************************/
$(function() {
  $('.navbar-toggler').click(function() {
    // toggle .shown on our menu and remove .shown on other menu
    var menu = $($(this).data('target'));
    menu.toggleClass('shown');
    menu.siblings().removeClass('shown');
  });
});


/********* collapsible card with icon-down-open visual indicator *********/
$(document).on(
  'show.bs.collapse hide.bs.collapse',
  '.card-collapse-icon>.collapse',
  function (e) {
    $(e.target).siblings('.card-header').toggleClass('active');
  }
);


/****************************** search form ******************************/
$(function() {

  $('<select>', {
    id: 'searchField',
    class: 'formControl',
    name: 'q',
    multiple: true,
  }).prependTo('#search-field-container');

  $('#searchField').select2({
    ajax: {
      url: URL_PREFIX + 'ajax/search',
      data: function (params) {
        // without this function, Select2 sends two unused arguments to the backend
        return { q: params.term };
      },
      delay: 300,
    },
    minimumInputLength: 1,
    selectionCssClass: 'navbar-selection',
    tags: true,
    templateResult: formatResult,
    width: 'resolve',
  });

  function formatResult(data) {
    if (data.html) {
      return $(data.html);
    }

    // fallback to the plain text for optgroups and other messages
    return data.text;
  }

  // redirect before the selection takes place, so that the user doesn't get
  // to see the pill being added to the pillbox
  $('#searchField').on('select2:selecting', function(e) {
    var data = e.params.args.data;
    if (data.url) {
      // existing option
      window.location.href = data.url;
    } else {
      // newly added option (possible because tags = true)
      window.location.href = SEARCH_URL + '/' + data.text;
    }
    return false;
  });

  // intercept the submit button because Select2 doesn't populate the <select>
  // element properly and the search term is not submitted.
  $('#form-search').submit(function() {
    var value = $('#searchField').find('option').val();
    window.location.href = SEARCH_URL + '/' + value;
    return false;
  });

});

/************ confirmations before discarding pending changes ************/
$(function() {
  var beforeUnloadHandlerAttached = false;

  $('[data-confirm]').click(function() {
    var msg = $(this).data('confirm');
    return confirm(msg);
  });

  // Expose this as a function so that other objects can also attach the
  // handler. For example, EasyMDE fields don't obey the .has-unload-warning
  // class.
  window.unsavedChangesHandler = function() {
    if (!beforeUnloadHandlerAttached) {
      beforeUnloadHandlerAttached = true;
      $(window).on('beforeunload', function() {
        // this is ignored in most browsers
        return 'Are you sure you want to leave?';
      });
    }
  }

  // ask for confirmation before navigating away from a modified field...
  $('.has-unload-warning').on('change input', unsavedChangesHandler);

  // ...except when actually submitting the form
  $('.has-unload-warning').closest('form').submit(function() {
    $(window).unbind('beforeunload');
  });

});

/*************************** vote submissions ***************************/
$(function() {
  // tooltips everywhere
  $('[data-toggle="tooltip"]').tooltip();

  $('.btn-vote').click(submitVote);
  $('.btn-vote[data-toggle="popover"]').popover({
    content: getPopoverContent,
    html: true,
    placement: 'right',
    title: $('#vote-popover-messages .title').html(),
    trigger: 'focus',
  });

  // don't show the popover when the content is empty
  $('.btn-vote[data-toggle="popover"]').on('show.bs.popover', function() {
    return (getPopoverContent.call($(this)) != '');
  });

  function getPopoverContent() {
    var msg = [];
    if ($(this).data('type') == TYPE_STATEMENT) {
      var html = $('#vote-popover-messages .body-statement').html();
      if (html) {
        msg.push(html.trim());
      }
    }
    if ($(this).data('value') == -1) {
      html = $('#vote-popover-messages .body-downvote').html();
      if (html) {
        msg.push(html.trim());
      }
    }
    return msg.join('<br><br>');
  }

  function submitVote() {
    var btn = $(this);
    $('body').addClass('waiting');
    $.post(URL_PREFIX + 'ajax/save-vote', {
      value: btn.data('value'),
      type: btn.data('type'),
      objectId: btn.data('objectId'),
    }).done(function(newScore) {

      // update the score
      $(btn.data('scoreId')).text(newScore);

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

/*************** toggling the "answer serves as proof" bit ***************/
$(function() {
  $('.btn-proof').click(toggleProof);

  function toggleProof() {
    var btn = $(this);
    $('body').addClass('waiting');
    $.post(URL_PREFIX + 'ajax/save-proof', {
      answerId: btn.data('answerId'),
    }).done(function() {

      btn.toggleClass('accepted');

    }).fail(function(errorMsg) {
      if (errorMsg.responseJSON) {
        alert(errorMsg.responseJSON);
      }
    }).always(function() {
      $('body').removeClass('waiting');
    });
  }

});

/******************* changing the reputation manually *******************/
$(function() {

  $('#rep-change').submit(changeReputation);

  function changeReputation(evt) {
    evt.preventDefault();

    $.post(URL_PREFIX + 'ajax/change-reputation', {
      reputation: $('#fakeReputation').val(),
      moderator: +$("#fakeModerator").is(':checked'), // the + converts it to integer
    }).done(function() {

      window.location.reload();

    }).fail(function(errorMsg) {
      if (errorMsg.responseJSON) {
        alert(errorMsg.responseJSON);
      }
    });

    return false;
  }

});

/*************************** loyalty popovers ***************************/
$(function() {
  $('.loyalty-widget[data-toggle="popover"]').popover({
    content: getPopoverContent,
    html: true,
    placement: 'bottom',
    trigger: 'hover',
  });

  function getPopoverContent() {
    return $(this).parent().find('.loyalty-popover').html();
  }
});

/****************************** file inputs ******************************/

$(function() {
  $('.custom-file-input').on('change', function() {
    // change the label value
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass('selected').html(fileName);
  });
});

/****************************** SortableJS ******************************/

$(function() {

  $('table.sortable tbody').each(function() {
    Sortable.create(this, {
      handle: '.icon-move',
	    animation: 150,
    });
  });

});

/************************* show remaining chars *************************/

$(function() {
  $('body').on('keyup paste change', '.size-limit', function() {

    // We trust the browser to obey maxlength. This is safe because we also
    // have a backend check.
    var l = $(this).val().length;
    var max = $(this).attr('maxlength');

    $(this).parent().find('.chars-remaining').text(max - l);
  });
});

/************************* Single-line textareas *************************/

$(function() {
  $('textarea.single-line').keypress(function(e) {
    return (e.keyCode != 13);
  });
});

/******************************* comments *******************************/

$(function() {
  var commentForm = $('#form-comment').detach().removeAttr('id');

  $('a.add-comment').click(addCommentForm);
  $('a.delete-comment').click(deleteComment);
  $('body').on('click', 'button.comment-save', saveComment);
  $('body').on('click', 'button.comment-cancel', hideCommentForm);
  $('body').on('hide.bs.dropdown', '.dropdown-canned-responses', copyCannedResponse);

  function addCommentForm() {
    // clone the form and populate some fields
    var c = commentForm.clone(true);
    c.find('input[name="objectType"]').val($(this).data('objectType'));
    c.find('input[name="objectId"]').val($(this).data('objectId'));

    // show it beneath the button div
    var anchor = $(this).closest('div');
    c.insertAfter(anchor);
    c.find('textarea').focus();

    // hide the link
    $(this).hide();

    return false;
  }

  function hideCommentForm() {
    var f = $(this).closest('form');

    // put the "add comment" link back in the previous div
    f.prev('div').find('a.add-comment').show();

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

      window.location.hash = 'c' + successMsg;
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

  function copyCannedResponse(e) {
    var wrap = $(e.clickEvent.target).closest('.canned-response-wrapper');
    var textarea =  wrap.closest('form').find('textarea');
    textarea.val(wrap.data('raw'));
  }

});

/**************************** number padding ****************************/

// horrible horrible hack
function pad(n, width) {
  return ('00000000000' + n).slice(-width);
}

/*************** Make Node.js better by circumventing it. ***************/

module = {};

function require(name) {
  // required by EasyMDE
  if (name == 'codemirror') {
    return CodeMirror;
  } else if (name == 'marked/lib/marked') {
    return marked;
  }

  return false;
}
