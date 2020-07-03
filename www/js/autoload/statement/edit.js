$(function() {
  function init() {
    initSelect2('#field-entity-id', URL_PREFIX + 'ajax/load-entities', {
      ajax: {
        url: URL_PREFIX + 'ajax/search-entities',
      },
      minimumInputLength: 2,
    });

    var opts = {
      format: 'yyyy-mm-dd',
      todayHighlight: true,
    };
    if (SELECT2_LOCALE) {
      opts['language'] = SELECT2_LOCALE;
    }
    $('#field-date-made').datepicker(opts);
  }

  init();

});
