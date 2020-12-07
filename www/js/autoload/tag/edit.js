$(function() {

  function init() {
    initSelect2('#parent-id', URL_PREFIX + 'ajax/load-tags', {
      ajax: { url: URL_PREFIX + 'ajax/search-tags', },
      allowClear: true,
      minimumInputLength: 1,
      width: '100%',
    });

    $('.frequent-color').click(frequentColorClick);
  }

  function frequentColorClick() {
    var input = $($(this).data('target'));
    input.val($(this).data('value'));
  }

  init();
});
