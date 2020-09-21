/**
 * Adds support for paginated results.
 *
 * Clicking on the pager redraws the page numbers, highlighting the newly
 * selected page.
 *
 * Optionally, inputs with the actionable class permit filtering the results.
 * In this case, clicking on the pager will pass the current field values to
 * the underlying search. Changing an input will also trigger a new search
 * and reset the page number to 1.
 */
$(function() {
  const PAGINATION_URL = URL_PREFIX + 'ajax/pagination';

  function init() {
    $('select.actionable[multiple]').select2();
    $('select.actionable[name="entityId"]').select2({
      ajax: {
        url: URL_PREFIX + 'ajax/search-entities',
      },
      allowClear: true,
      minimumInputLength: 2,
    });

    $('.pagination-wrapper').on('click', 'a', paginationClick);
    $('.actionable').change(filterChange);
    $('input.actionable').on('keypress paste', delay(function(e) {
      filterChange(e);
    }, 500));
  }

  function paginationClick() {
    var wrapper = $(this).closest('.pagination-wrapper');
    var numPages = $(this).closest('ul').data('numPages');

    // the prev/next page links contain the page number in a data- attribute
    var page = $(this).data('dest') || $(this).html();

    // load the pagination box first
    $('body').addClass('waiting');
    $.get(PAGINATION_URL, {
      n: numPages,
      k: page,
    }).done(function(html) {

      wrapper.html(html);

      // now load the new page contents
      var url = wrapper.data('url');
      var target = $(wrapper.data('target'));
      var args = getArgs(wrapper.siblings('form'), page);

      $.get(url, args).done(function(json) {
        target.html(json.html);
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

  function filterChange(e) {
    var form = $(e.target).closest('form')
    var pagWrap = form.siblings('.pagination-wrapper');
    var target = $(pagWrap.data('target'));

    var args = getArgs(form, 1);

    // load the search results
    $('body').addClass('waiting');
    $.get(URL_PREFIX + 'ajax/search-statements', args)
      .done(function(json) {

        // show the first page
        target.html(json.html);

        // show the new pager and activate page 1
        $.get(PAGINATION_URL, {
          n: json.numPages,
          k: 1,
        }).done(function(html) {
          pagWrap.html(html);
        });

      }).always(function() {
        $('body').removeClass('waiting');
      });

    return false;
  }

  function getArgs(form, page) {
    var args = {
      page: page,
    }

    form.find('.actionable').each(function() {
      // strip [] from array inputs
      var name = $(this).attr('name').replace(/[\[\]]+/g,'');
      args[name] = $(this).val();
    });

    return args;
  }

  // kudos https://stackoverflow.com/questions/1909441/how-to-delay-the-keyup-handler-until-the-user-stops-typing
  function delay(fn, ms) {
    let timer = 0
    return function(...args) {
      clearTimeout(timer)
      timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
  }

  init();

});
