$(function() {

  const PAGINATION_URL = URL_PREFIX + 'ajax/pagination';

  function init() {
    $('.pagination-wrapper').on('click', 'a', paginationClick);
  }

  function paginationClick() {
    var wrapper = $(this).closest('.pagination-wrapper');
    var n = wrapper.data('numPages');

    // the prev/next page links contain the page number in a data- attribute
    var page = $(this).data('dest') || $(this).html();

    // load the pagination box first
    $('body').addClass('waiting');
    $.get(PAGINATION_URL, {
      n: n,
      k: page,
    }).done(function(html) {

      wrapper.html(html);

      // now load the new page contents
      var url = wrapper.data('url');
      var target = $(wrapper.data('target'));
      $.get(url, {
        p: page,
      }).done(function(html) {

        target.html(html);
        success = true;

      }).fail(function() {
        alert('Cannot load the request page contents.');
      });

    }).fail(function() {
      alert('Cannot load the pagination box.');
    }).always(function() {
      $('body').removeClass('waiting');
    });

    return false;
  }

  init();

});
