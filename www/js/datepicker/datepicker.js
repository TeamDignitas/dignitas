/**
 * Datepickers consist of
 *
 * (1) a visible <input type="text" class="form-control datepicker"...>;
 * (2) a hidden <input type="hidden"...> immediately following (1).
 *
 * This gives us the flexibility of a human-readable display value ("July 3,
 * 2020") and a numeric submit value ("2020-07-03").
 **/
$(function() {

  const MODAL= $(
    '<div class="modal" tabindex="-1">' +
      '<div class="modal-dialog modal-dialog-centered">' +
      '  <div class="modal-content">' +
      '    <div class="modal-header">' +
      '      <h5 class="modal-title"></h5>' +
      '      <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
      '        <span aria-hidden="true">&times;</span>' +
      '      </button>' +
      '    </div>' +
      '    <div class="modal-body d-flex">' +
      '      <div class="p-2">' +
      '        <label class="datepicker-year-label"></label>' +
      '        <select class="datepicker-year form-control" tabindex="-1"></select>' +
      '      </div>' +
      '      <div class="p-2">' +
      '        <label class="datepicker-month-label"></label>' +
      '        <select class="datepicker-month form-control"></select>' +
      '      </div>' +
      '      <div class="p-2">' +
      '        <label class="datepicker-day-label"></label>' +
      '        <select class="datepicker-day form-control"></select>' +
      '      </div>' +
      '    </div>' +
      '    <div class="modal-footer">' +
      '      <button type="button" class="btn btn-light btn-clear"></button>' +
      '      <button type="button" class="btn btn-secondary btn-today"></button>' +
      '      <button type="button" class="btn btn-primary btn-accept"></button>' +
      '    </div>' +
      '  </div>' +
      '</div>' +

    '</div>'
  );
  const MIN_YEAR = 1940;

  var invoker; // element that triggered the modal, where the date is to be stored

  init();

  function init() {
    MODAL.on('show.bs.modal', modalShow);
    MODAL.find('.datepicker-month').change(monthChanged);
    MODAL.find('.datepicker-year').change(populateDays);
    MODAL.find('.btn-accept').click(acceptClicked);
    MODAL.find('.btn-clear').click(clearClicked);
    MODAL.find('.btn-today').click(todayClicked);

    $.fn.datepicker = function(options) {
      this.focus(function() {
        invoker = $(this);
        invoker.options = options;
        MODAL.modal('show');
      });

      return this;
    };

    // these can be overridden by including a file from intl/
    $.fn.datepickerOptions = $.fn.datepickerOptions || {
      months: [
        'unknown',
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August', 'September',
        'October', 'November', 'December',
      ],

      labels: {
        title: 'choose a date',

        year: 'year',
        month: 'month',
        day: 'day',

        accept: 'accept',
        clear: 'clear',
        today: 'today',
      },

      format: function(y, m, d) {
        if (!m) {
          return y;
        } else if (!d) {
          return this.months[m] + ' ' + y;
        } else {
          return d + ' ' + this.months[m] + ' ' + y;
        }
      },
    };

    $('.datepicker').each(function() {
      setDisplay($(this));

      var allowPartial = $(this).data('allowPartial') ?? true;
      $(this).datepicker({
        allowPartial: allowPartial,
      });
    });
  }

  function modalShow(e) {
    // lazy select population to give the localized names time to load
    populateModal();
    setPickerFromInput();
  }

  // Sets the display value from the hidden value. Used during initialization.
  function setDisplay(element) {
    var val = element.next().val();
    var d = parseDate(val);

    var display = d.year
        ? $.fn.datepickerOptions.format(d.year, d.month, d.day)
        : '';
    element.val(display);
  }

  // input: the visible input; we'll work with the hidden sibling
  function setPickerFromInput() {
    var val = invoker.next().val();
    var d = parseDate(val);

    if (d.year) {
      MODAL.find('.datepicker-year').val(d.year);
      MODAL.find('.datepicker-month').val(d.month).trigger('change');
      populateDays();
      MODAL.find('.datepicker-day').val(d.day);
    } else {
      // same effect as clicking the today button
      todayClicked();
    }

    // show/hide unkown month/day based on partial dates option
    var hidden = !invoker.options.allowPartial;
    MODAL.find('.datepicker-month option').first().attr('hidden', hidden);
    MODAL.find('.datepicker-day option').first().attr('hidden', hidden);
  }

  function populateModal() {
    // create year options
    var d = new Date();
    var year = d.getFullYear();
    var sel = MODAL.find('.datepicker-year');

    if (sel.find('option').length) {
      return; // already created
    }

    for (var i = year; i >= MIN_YEAR; i--) {
      $('<option/>', { value : i }).text(i).appendTo(sel);
    }

    // create month options
    sel = MODAL.find('.datepicker-month');
    for (i = 0; i <= 12; i++) {
      var name = $.fn.datepickerOptions.months[i];
      $('<option/>', { value : i }).text(name).appendTo(sel);
    }

    // populate buttons
    var labels = $.fn.datepickerOptions.labels;
    MODAL.find('.modal-title').html(labels.title);
    MODAL.find('.datepicker-year-label').html(labels.year);
    MODAL.find('.datepicker-month-label').html(labels.month);
    MODAL.find('.datepicker-day-label').html(labels.day);
    MODAL.find('.btn-accept').html(labels.accept);
    MODAL.find('.btn-clear').html(labels.clear);
    MODAL.find('.btn-today').html(labels.today);
  }

  // Ensures the day select has the correct number of options for the
  // currently selected year and month. The month is 1-based.
  function populateDays() {
    var year = parseInt(MODAL.find('.datepicker-year').val());
    var month = parseInt(MODAL.find('.datepicker-month').val());
    var sel = MODAL.find('.datepicker-day');
    var cur = sel.find('option').length;

    // See https://stackoverflow.com/a/1184359/6022817 for why this works
    // When the month is unknown (0), this will return 31 for December.
    var target = 1 + new Date(year, month, 0).getDate();

    if (!cur) {
      cur = 1;
      var text = $.fn.datepickerOptions.months[0];
      $('<option/>', { value : 0 }).text(text).appendTo(sel);
    }

    // remove excess options
    if (cur > target) {
      var day = MODAL.find('.datepicker-day').val();
      MODAL.find('.datepicker-day option').slice(target - cur).remove();

      // when switching from March 31 to February, set the day to 28, don't
      // let it go to 0/unknown
      var last = MODAL.find('.datepicker-day option').last().val();
      if (day > last) {
        MODAL.find('.datepicker-day').val(last);
      }
    }

    // add missing options
    while (cur < target) {
      $('<option/>', { value : cur }).text(cur).appendTo(sel);
      cur++;
    }
  }

  function monthChanged() {
    populateDays();

    var val = parseInt($(this).val());
    if (!val) {
      MODAL.find('.datepicker-day').prop('disabled', true).val(0);
    } else {
      MODAL.find('.datepicker-day').prop('disabled', false);
    }
  }

  function acceptClicked() {
    var year = parseInt(MODAL.find('.datepicker-year').val());
    var month = parseInt(MODAL.find('.datepicker-month').val());
    var day = parseInt(MODAL.find('.datepicker-day').val());

    // compute the hidden and display values
    var hiddenVal = year + '-' + pad(month, 2) + '-' + pad(day, 2);
    invoker.next().val(hiddenVal).trigger('change');
    setDisplay(invoker);

    MODAL.modal('hide');
  }

  function clearClicked() {
    invoker.val('');
    invoker.next().val('').trigger('change');
    MODAL.modal('hide');
  }

  function todayClicked() {
    var d = new Date();
    var year = d.getFullYear();
    var month = 1 + d.getMonth(); // one-based
    var day = d.getDate();

    MODAL.find('.datepicker-year').val(year);
    MODAL.find('.datepicker-month').val(month).trigger('change');
    populateDays();
    MODAL.find('.datepicker-day').val(day);
  }

  /**
   * Parses a date in one of the formats: '', YYYY, YYYY-MM, YYYY-MM-DD.
   * 0000-00-00 and '' both signify empty dates.
   * Returns a dictionary.
   **/
  function parseDate(s) {
    var parts = s.split('-');

    return {
      year: parseInt(parts[0]) || 0,
      month: parseInt(parts[1] ?? 0),
      day: parseInt(parts[2] ?? 0),
    }
  }

});
