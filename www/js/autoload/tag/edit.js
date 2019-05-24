$(function() {

  function init() {
    initSelect2('#parentId', URL_PREFIX + 'ajax/load-tags', {
      ajax: { url: URL_PREFIX + 'ajax/search-tags', },
      allowClear: true,
      minimumInputLength: 1,
      width: '100%',
    });

    $('#color').closest('.colorpicker-component').colorpicker({
      align: 'left',
      colorSelectors: collectFrequentColors('#frequent-color'),
      format: 'hex',
    });
    $('#background').closest('.colorpicker-component').colorpicker({
      align: 'left',
      colorSelectors: collectFrequentColors('#frequent-background'),
      format: 'hex',
    });
  }

  function collectFrequentColors(sel) {
    var result = [];
    $(sel).find('div').each(function() {
      result.push($(this).text());
    });
    return result;
  }

  init();
});
