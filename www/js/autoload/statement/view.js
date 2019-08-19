$(function() {

  var objectType;
  var objectId;

  // hide and clear extra fields (other -> details, duplicate -> select2)
  function clearRelatedFields() {
    $('.flagRelated').attr('hidden', true);
    $('#flagDuplicateId').val(null).trigger('change');
    $('#flagDetails').val('');
  }

  function submitFlag() {
    var btn = $(this);
    $('body').addClass('waiting');
    $.post(URL_PREFIX + 'ajax/save-flag', {
      objectType: objectType,
      objectId: objectId,
      reason: $('input[name="flagReason"]:checked').val(),
      duplicateId: $('#flagDuplicateId').val(),
      details: $('#flagDetails').val(),

    }).done(function() {

      $('#flagModal').modal('hide');

    }).fail(function(errorMsg) {

      if (errorMsg.responseJSON) {
        alert(errorMsg.responseJSON);
      }

    }).always(function() {

      $('body').removeClass('waiting');

    });
  }

  initSimpleMde('fieldContents');

  // reset the form before displaying the modal (user could open it multiple times)
  $('#flagModal').on('show.bs.modal', function(evt) {
    var caller = $(evt.relatedTarget);
    objectType = caller.data('objectType');
    objectId = caller.data('objectId');

    $('*[data-flag-visibility]').hide();
    $('*[data-flag-visibility="' + objectType + '"]').show();
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
      url: URL_PREFIX + 'ajax/search-statements',
      delay: 300,
    },
    minimumInputLength: 1,
    width: 'resolve',
  });

  $('#flagDuplicateId').on('select2:select', function() {
    $('#flagButton').prop('disabled', false);
  });

  $('#flagButton').click(submitFlag);

});
