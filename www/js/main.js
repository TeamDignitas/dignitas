/******************************* snackbars *******************************/
$(function() {
  $('#snackbars .toast').toast('show');
});

function snackbar(msg) {
  if (msg) {
    var t = $('#snackbar-stub .toast').clone();
    t.find('.toast-body').html(msg);
    t.appendTo('#snackbars').toast('show');
  }
}


/*************************** nav sidebar slide ***************************/
$(function() {
  $('.navbar-toggler').click(function() {
    // hide the other sidebar
    var otherId = $(this).siblings('.navbar-toggler').data('bs-target');
    var bsCollapse = new bootstrap.Collapse(otherId, {
      toggle: false,
    });
    bsCollapse.hide();
  });
});


/******** collapsible card with expand/collapse visual indicator ********/
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
    class: 'formSelect',
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


/***************** alert() written as a Bootstrap modal *****************/
$(function() {

  window.alert = function(msg) {
    var m = $(
      '<div class="modal fade" data-bs-backdrop="static" tabindex="-1">' +
        '<div class="modal-dialog modal-dialog-centered modal-sm">' +
        '<div class="modal-content shadow">' +
        '<div class="modal-body">' +
        msg +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-sm btn-primary btn-confirm px-4" data-bs-dismiss="modal">' +
        _('alert-ok-text') +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    m.modal('show');
  }

});


/*********** confirmations before obeying click() and change() ***********/
$(function() {

  $('a[data-confirm], button[data-confirm]').click(launchConfirm);
  $('select[data-confirm]').change(launchConfirm);

  function launchConfirm(evt) {
    if (evt.isTrigger) {
      // We were called as a result of the user making a choice.
      // Step aside and let the other handler run on the object.
      return true;
    }

    // prevent the other handlers from running
    evt.stopImmediatePropagation();

    // The modal can be closed by clicking either button OR by clicking outside
    // it. Therefore, the only way to guarantee a callback is to handle the
    // modal's hidden event.
    var choice = false;
    var target = $(this);

    var m = $(
      '<div class="modal fade" tabindex="-1">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-body">' +
        $(this).data('confirm') +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">' +
        _('confirm-cancel-text') +
        '</button>' +
        '<button type="button" class="btn btn-sm btn-primary btn-confirm px-4" data-bs-dismiss="modal">' +
        _('confirm-ok-text') +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    m.find('.btn-confirm').click(function() {
      choice = true;
    });

    m.on('hide.bs.modal', function() {
      // Call the handlers again if necessary. This will include ourselves,
      // but we'll just return.
      if (evt.type == 'change') {
        // For change events, always trigger the callback. Selects cannot
        // prevent a change, only roll it back afterwards.
        target.trigger('change', choice);
      } else if ((evt.type == 'click') && choice) {
        target.click();
      }
    });

    m.modal('show');

    return false;
  }
});


/************ confirmations before discarding pending changes ************/
$(function() {
  var beforeUnloadHandlerAttached = false;

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

  // Ask for confirmation before navigating away from a modified field. Do
  // this with a delay because the remaining chars module calls change() upon
  // initialization.
  setTimeout(function() {
    $('.has-unload-warning').on('change input', unsavedChangesHandler);
  }, 2000);

  // ...except when actually submitting the form
  $('.has-unload-warning').closest('form').submit(function() {
    $(window).unbind('beforeunload');
  });

});


/*************************** vote submissions ***************************/
$(function() {
  // tooltips everywhere
  $('[data-bs-toggle="tooltip"]').tooltip();

  $('.btn-vote').click(submitVote);

  function submitVote() {
    var btn = $(this);

    // hide tooltip on the wrapper div
    btn.parent('[data-bs-toggle="tooltip"]').tooltip('hide');

    $('body').addClass('waiting');
    $.post(URL_PREFIX + 'ajax/save-vote', {
      value: btn.data('value'),
      type: btn.data('type'),
      objectId: btn.data('objectId'),
    }).done(function(newScore) {

      // update the score
      $(btn.data('scoreId')).text(newScore);

      // enable the opposite button
      btn.parents('.vote-box').find('.btn-vote.voted').not(btn).removeClass('voted');

      // toggle this button
      btn.toggleClass('voted');

      // show a snackbar if needed
      if ((btn.data('type') != TYPE_COMMENT) && btn.hasClass('voted')) {
        var id = (btn.data('value') == 1) ? '#upvote-snackbar' : '#downvote-snackbar';
        var msg = $(id).html();
        snackbar(msg);
      }

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
      var id = btn.hasClass('accepted') ? '#accept-snackbar' : '#unaccept-snackbar';
      var msg = $(id).html();
      snackbar(msg);

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

  // clicking the checkbox label should not close the dropdown
  $('#fake-moderator-wrapper').click(function(e) {
    e.stopPropagation();
  });

});

/*************************** loyalty popovers ***************************/
$(function() {
  $('.loyalty-widget[data-bs-toggle="popover"]').popover({
    content: getPopoverContent,
    html: true,
    placement: 'bottom',
    trigger: 'hover',
  });

  function getPopoverContent() {
    return $(this).parent().find('.loyalty-popover').html();
  }
});

/****************************** SortableJS ******************************/

$(function() {

  $('table.sortable tbody').each(function() {
    Sortable.create(this, {
      handle: '.drag-indicator',
	    animation: 150,
    });
  });

});

/************************* show remaining chars *************************/

$(function() {
  $('body').on('keyup paste change', '.size-limit', function() {

    var remaining = $(this).attr('maxlength') - $(this).val().length;
    var span = $(this).parent().find('.chars-remaining');

    if (remaining >= 0) {
      // Hide errors when the constraint is satisfied. This is necessary because
      // the browser extension is allowed to submit strings longer than the
      // limit.
      span.text(_('remaining-chars', remaining));
      span.removeClass('text-danger');
    } else {
      span.text(_('exceeding-chars', -remaining));
      span.addClass('text-danger');
    }

    // Never prevent the change. We trust the browser to obey maxlength. This
    // is safe because we also have a backend check.
  });

  $('.size-limit').change();

});

/************************* Single-line textareas *************************/

$(function() {
  $('textarea.single-line').keypress(function(e) {
    return (e.keyCode != 13);
  });
});

/* Activate the first tab when we don't know which one is first */

$(function() {
  $('.activate-first-tab .nav-link:first-child').tab('show');
});

/******************************* comments *******************************/

$(function() {
  var commentForm = $('#form-comment').detach().removeAttr('id');

  $('a.add-comment').click(addCommentForm);
  // this will ask for confirmation first because the data-confirm handler is
  // registered first
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

  function deleteComment(evt) {
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
    textarea.val(wrap.data('raw')).change().focus();
  }

});

/**************************** archived links ****************************/

$(function() {
  const DELAY_SHOW = 1000;
  const DELAY_HIDE = 300;
  var timer;
  var link = null;

  $('a.archivable, .archivable a').hover(mouseIn, mouseOut);

  function mouseIn(e) {
    // clear any existing popover
    if (link) {
      link.popover('dispose');
    }

    // delay, then check if archived link exists
    link = $(e.target);
    timer = setTimeout(archiveLookup, DELAY_SHOW);
  }

  function mouseOut() {
    // cancel the archived link check
    clearTimeout(timer);
  }

  function archiveLookup() {
    // check if archived link exists
    $.get(URL_PREFIX + 'ajax/archive-lookup', {
      url: link.attr('href'),
    }).done(function(data) {
      var url = data.archivedUrl;

      if (url) {
        createPopover(url);
      }
    });
  }

  function createPopover(url) {
    // Create and show the popover.
    link.popover({
      content: _('archived-version-tooltip', url),
      html: true,
      placement: 'auto',
    });
    link.popover('show');
    var pop = $(link.data('bs.popover').tip);
    pop.addClass('popover-archive shadow');

    // Hide the popover when the mouse leaves *the popover*.
    $('.popover').mouseleave(function () {
      link.popover('hide');
    });

    // Hide the popover when the mouse leaves *the link*,
    // unless it enters the popover.
    // See https://embed.plnkr.co/plunk/HLqrJ6
    link.mouseleave(function () {
      setTimeout(function () {
        if (!$('.popover:hover').length) {
          link.popover('dispose');
        }
      }, DELAY_HIDE);
    });
  }

});

/************** Clear error messages when fields are edited **************/

$(function() {
  $('.is-invalid, .table-rel .form-control, #link-container .form-control')
    .on('change input paste', clearErrors);
  $('.delete-dependant, #link-container .delete-link').click(clearErrors);

  function clearErrors() {
    $(this)
      .removeClass('is-invalid')
      .closest('.form-group, .form-check')
      .find('.field-error')
      .fadeOut();
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
