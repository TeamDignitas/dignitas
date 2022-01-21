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
    $('select.actionable[name="entityId"]').select2({
      ajax: {
        url: URL_PREFIX + 'ajax/search-entities',
      },
      allowClear: true,
      dropdownCssClass: 'select2--small',
      minimumInputLength: 2,
      selectionCssClass: 'select2--small',
    });
    $('select.actionable[name="type"]').change(typeChange);

    $('.pagination-wrapper').on('click', 'a', paginationClick);
    $('.actionable').change(filterChange);
    $('input.actionable').on('keydown paste', delay(function(e) {
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
      var target = $(wrapper.data('bs-target'));
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

  function typeChange(e) {
    var v = $('#statement-filters-verdicts');
    v.find('option').remove();

    // It is safe to populate the verdict list asynchronously and let the Ajax
    // filter run in the meantime. We do not need to select any verdicts anyway.

    $.get(URL_PREFIX + 'ajax/get-verdicts', {
      statementType: $(this).val(),
      selectpicker: true,
    }).done(function(data) {
      for (var i = 0; i < data.length; i++) {
        v.append(
          '<option value="' + data[i].value + '" ' +
            'data-content="' + data[i].html + '">' +
            data[i].text + '</option>'
        );
      }
      v.selectpicker('refresh');
    });
  }

  function filterChange(e) {
    var form = $(e.target).closest('form')
    var pagWrap = form.siblings('.pagination-wrapper');
    var target = $(pagWrap.data('bs-target'));

    var args = getArgs(form, 1);

    // load the search results
    $('body').addClass('waiting');
    $.get(form.data('url'), args)
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

    form.find('input.actionable, select.actionable').each(function() {
      // strip [] from array inputs
      var name = $(this).attr('name').replace(/[\[\]]+/g,'');

      args[name] = $(this).is(':checkbox')
        ? +$(this).is(':checked') // convert to int
        : $(this).val();
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
