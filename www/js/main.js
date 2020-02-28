/*************************** vanishing alerts ***************************/
$(function() {
  setTimeout(function() {
    $('#flashMessageWrapper > .alert').fadeTo(1000, 0, function() {
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

    // occupy entire page height
    var pageHeight = Math.max($(window).height(), $(document).height());
    var h = pageHeight - menu.position().top;
    menu.outerHeight(h);
  });
});

/****************************** search form ******************************/
$(function() {

  $('<select>', {
    id: 'searchField',
    class: 'formControl',
    name: 'q',
    multiple: true,
  }).prependTo('#searchFieldContainer');

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
  $('#searchForm').submit(function() {
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
  // handler. For example, SimpleMDE fields don't obey the .hasUnloadWarning
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
  $('.hasUnloadWarning').on('change input', unsavedChangesHandler);

  // ...except when actually submitting the form
  $('.hasUnloadWarning').closest('form').submit(function() {
    $(window).unbind('beforeunload');
  });

});

/*************************** vote submissions ***************************/
$(function() {
  $('.btn-vote').click(submitVote);

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

  $('#repChange').submit(changeReputation);

  function changeReputation(evt) {
    evt.preventDefault();

    var rep = $(this).find('input').val();
    $.post(URL_PREFIX + 'ajax/change-reputation', {
      value: rep,
    }).done(function(newRep) {

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
  $('[data-toggle="popover"]').popover({
    content: getPopoverContent,
    html: true,
    placement: 'bottom',
    trigger: 'hover',
  });

  function getPopoverContent() {
    return $(this).parent().find('.loyaltyPopover').html();
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
