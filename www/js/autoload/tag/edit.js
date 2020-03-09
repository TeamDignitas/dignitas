$(function() {

  function init() {
    initSelect2('#parent-id', URL_PREFIX + 'ajax/load-tags', {
      ajax: { url: URL_PREFIX + 'ajax/search-tags', },
      allowClear: true,
      minimumInputLength: 1,
      width: '100%',
    });

    $('#color').closest('.colorpicker-component').colorpicker({
      extensions: [
        {
          name: 'swatches',
          options: { colors: collectFrequentColors('#frequent-color') },
        }
      ],
      fallbackColor: '#ffffff',
    });
    $('#background').closest('.colorpicker-component').colorpicker({
      extensions: [
        {
          name: 'swatches',
          options: { colors: collectFrequentColors('#frequent-background') },
        }
      ],
      fallbackColor: '#1e83c2',
    });
  }

  function collectFrequentColors(sel) {
    var result = {};
    $(sel).find('div').each(function() {
      var hex = $(this).text();
      result[hex] = hex;
    });
    return result;
  }

  init();
});
