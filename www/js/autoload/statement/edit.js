$(function() {
  function init() {
    initSelect2('#field-entity-id', URL_PREFIX + 'ajax/load-entities', {
      ajax: {
        url: URL_PREFIX + 'ajax/search-entities',
      },
      minimumInputLength: 2,
    });

    $('#field-type').change(typeChange);
  }

  /**
   * Handles statement type changes. There may or may not be a confirmation
   * dialog (it's not always required), in which case choice holds the answer
   * from the dialog. If no confirmation is needed, choice defaults to true.
   *
   * @param bool choice Choice from the confirmation modal.
   **/
  function typeChange(evt, choice = true) {
    if (choice) {
      $(this).data('prevValue', $(this).val());
      refreshVerdicts();
    } else {
      $(this).val($(this).data('prevValue'));
    }
  }

  /**
   * Refresh the verdicts <select> to match the statement type.
   **/
  function refreshVerdicts() {
    $.get(URL_PREFIX + 'ajax/get-verdicts', {
      statementType: $('#field-type').val(),
    }).done(function(data) {
      $('#field-verdict option').remove();
      for (var i = 0; i < data.length; i++) {
        $("#field-verdict").append(new Option(data[i].text, data[i].value));
      }
    });
  }

  init();

});
