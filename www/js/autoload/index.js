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
    templateResult: formatResult,
    minimumInputLength: 1,
  });

  // redirect before the selection takes place, so that the user doesn't get
  // to see the pill being added to the pillbox
  $('#searchField').on('select2:selecting', function(e) {
    var data = e.params.args.data;
    window.location.href = data.url;
    return false;
  });

  function formatResult(data) {
    if (data.html) {
      return $(data.html);
    }

    // fallback to the plain text for optgroups and other messages
    return data.text;
  }
});
