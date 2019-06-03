$(function() {
  $('#searchField').select2({
    ajax: {
      url: URL_PREFIX + 'ajax/search',
      data: function (params) {
        // without this function, Select2 sends two unused arguments to the backend
        return { q: params.term };
      },
      delay: 300,
    },
    minimumInputLength: 1,
  });
});
