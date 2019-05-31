$(function() {
  $('#searchField').select2({
    ajax: {
      url: URL_PREFIX + 'ajax/search',
      data: function (params) {
        return { q: params.term };
      },
      delay: 300,
    },
    minimumInputLength: 1,
  });
});
