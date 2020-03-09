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
    $('#flagDuplicateId').val(null).trigger('change');
    $('#flagDetails').val('');
  }

  function submitFlag() {
    $('body').addClass('waiting');
    $.post(URL_PREFIX + 'ajax/save-flag', {
      objectType: objectType,
      objectId: objectId,
      reason: $('input[name="flagReason"]:checked').val(),
      duplicateId: $('#flagDuplicateId').val(),
      details: $('#flagDetails').val(),

    }).done(function(successMsg, textStatus, xhr) {
      if (xhr.status == 202) {
        // backend signalled us that we should refresh
        location.reload(true);
      }
      flagLink.prop('hidden', true);
      unflagLink.prop('hidden', false);
      $('#flagModal').modal('hide');
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

    }).done(function(successMsg) {

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
    $('#confirmModal .modal-body').html(msg);
    $('#confirmModal').modal('show');

    setTimeout(function() {
      $('#confirmModal').modal('hide');
    }, 1500);
  }

  // reset the form before displaying the modal (user could open it multiple times)
  $('#flagModal').on('show.bs.modal', function(evt) {
    flagLink = $(evt.relatedTarget);
    unflagLink = $(flagLink.data('unflagLink'));
    objectType = flagLink.data('objectType');
    objectId = flagLink.data('objectId');

    $('*[data-flag-visibility]').hide();
    $('*[data-flag-visibility~="' + objectType + '"]').show();
    $('input[type=radio][name=flagReason]').prop('checked', false);
    $('#flagButton').attr('disabled', true);

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
    $('#flagButton').prop('disabled', Boolean(relatedId));
  });

  // in contrast to keyup, the input event also detects cut/paste/undo/redo etc.
  $('#flagDetails').on('input', function() {
    $('#flagButton').prop('disabled', !$(this).val());
  });

  $('#flagDuplicateId').select2({
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
    dropdownParent: $('#flagModal'),
    minimumInputLength: 1,
    width: 'resolve',
  });

  $('#flagDuplicateId').on('select2:select', function() {
    $('#flagButton').prop('disabled', false);
  });

  $('#flagButton').click(submitFlag);
  $('a.unflag').click(submitUnflag);

  // Prevent form submission, e.g. by pressing Enter while in the "other
  // reason" field. Instead, submit the flag via an Ajax call.
  $('#flagModal').submit(submitFlag);

});
