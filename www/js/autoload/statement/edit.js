$(function() {
  function init() {
    initSelect2('#field-entity-id', URL_PREFIX + 'ajax/load-entities', {
      ajax: {
        url: URL_PREFIX + 'ajax/search-entities',
      },
      minimumInputLength: 2,
    });
  }

  init();

});
