$(function() {

  var objectType;
  var objectId;

  // Every flag link has an opposite unflag link. When the page is first
  // displayed we show the correct link depending on whether the object is
  // currently flagged. However, we need Javascript to offer the opposite
  // action without reloading the page.
  var flagLink;
  var unflagLink;

  // hide and clear extra fields (other -> details, duplicate -> select2)
  function clearRelatedFields() {
    $('.flag-related').attr('hidden', true);
    $('#flag-duplicate-id').val(null).trigger('change');
    $('#flag-details').val('');
  }

  function submitFlag() {
    $('body').addClass('waiting');
    $.post(URL_PREFIX + 'ajax/save-flag', {
      objectType: objectType,
      objectId: objectId,
      reason: $('input[name="flagReason"]:checked').val(),
      duplicateId: $('#flag-duplicate-id').val(),
      details: $('#flag-details').val(),

    }).done(function(successMsg, textStatus, xhr) {
      if (xhr.status == 202) {
        // backend signalled us that we should refresh
        location.reload(true);
      }
      flagLink.prop('hidden', true);
      unflagLink.prop('hidden', false);
      $('#modal-flag').modal('hide');
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

  function submitUnflag() {
    unflagLink = $(this);
    flagLink = $(unflagLink.data('flagLink'));
    objectType = unflagLink.data('objectType');
    objectId = unflagLink.data('objectId');

    $('body').addClass('waiting');

    $.post(URL_PREFIX + 'ajax/delete-flag', {
      objectType: objectType,
      objectId: objectId,

    }).done(function(successMsg, textStatus, xhr) {
      if (xhr.status == 202) {
        // backend signalled us that we should refresh
        location.reload(true);
      }

      // swap link visibility
      flagLink.prop('hidden', false);
      unflagLink.prop('hidden', true);
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
    $('#modal-confirm .modal-body').html(msg);
    $('#modal-confirm').modal('show');

    setTimeout(function() {
      $('#modal-confirm').modal('hide');
    }, 1500);
  }

  // reset the form before displaying the modal (user could open it multiple times)
  $('#modal-flag').on('show.bs.modal', function(evt) {
    flagLink = $(evt.relatedTarget);
    unflagLink = $(flagLink.data('unflagLink'));
    objectType = flagLink.data('objectType');
    objectId = flagLink.data('objectId');

    $('*[data-flag-visibility]').hide();
    $('*[data-flag-visibility~="' + objectType + '"]').show();
    $('input[type=radio][name=flagReason]').prop('checked', false);
    $('#button-flag').attr('disabled', true);

    // clear the details fields
    clearRelatedFields();
  })

  // show the related fields, if any
  $('input[type=radio][name=flagReason]').change(function() {
    clearRelatedFields();

    var relatedId = $(this).data('related');
    if (relatedId) {
      $(relatedId).removeAttr('hidden');
    }

    // only enable the button if the radio button has no related field
    $('#button-flag').prop('disabled', Boolean(relatedId));
  });

  // in contrast to keyup, the input event also detects cut/paste/undo/redo etc.
  $('#flag-details').on('input', function() {
    $('#button-flag').prop('disabled', !$(this).val());
  });

  $('#flag-duplicate-id').select2({
    ajax: {
      url: function(params) {
        return (objectType == TYPE_ENTITY)
          ? URL_PREFIX + 'ajax/search-entities'
          : URL_PREFIX + 'ajax/search-statements';
      },
      data: function(params, page) {
        return {
          term: params.term,
          exceptId: objectId,
        };
      },
      delay: 300,
    },
    // Without dropdownParent, the select2 won't receive focus because the
    // modal has tabIndex="-1" (and it needs to have that so that we can close
    // it by pressing Escape).
    dropdownParent: $('#modal-flag'),
    minimumInputLength: 1,
    width: 'resolve',
  });

  $('#flag-duplicate-id').on('select2:select', function() {
    $('#button-flag').prop('disabled', false);
  });

  $('#button-flag').click(submitFlag);
  $('a.unflag').click(submitUnflag);

  // Prevent form submission, e.g. by pressing Enter while in the "other
  // reason" field. Instead, submit the flag via an Ajax call.
  $('#modal-flag').submit(submitFlag);

});
