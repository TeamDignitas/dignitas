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
   * Ask for confirmation before changing the statement type. Skip this when
   * the statement is being created (it has no ID) or when there are no
   * answers with verdicts (i.e., no confirmation message is set).
   **/
  function typeChange(evt) {
    var id = $('#field-id').val();

    if (id) {
      var msg = $(this).data('confirmMsg');

      if (!msg || confirm(msg)) {
        $(this).data('prevValue', $(this).val());
        refreshVerdicts();
      } else {
        $(this).val($(this).data('prevValue'));
      }
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
