// Datepickers consist of
//
// (1) a visible <input type="text" class="form-control date-picker"...>;
// (2) a hidden <input type="hidden"...> immediately following (1).
//
// This gives us the flexibility of a human-readable display value ("July 3,
// 2020") and a numeric submit value ("2020-07-03"). Bootstrap-datepicker
// submits the display value, so we cannot use a single field.
$(function() {

  init();

  function init() {
    // initialize the date pickers
    $('.date-picker').datepicker({
      autoclose: true,
      clearBtn: true,
      format: DATEPICKER_FORMAT,
      language: DATEPICKER_LOCALE,
      todayHighlight: true,
    });

    // initialize the visible dates from the hidden values
    $('.date-picker').each(function() {
      var date = new Date($(this).next('input[type="hidden"]').val());
      $(this).datepicker('setDate', date);
    });

    // update the hidden value whenever a display value changes
    $('.date-picker').on('changeDate', function(e) {
      $(this).next('input[type="hidden"]').val(getDateString(e.date)).change();
    });
  }

  // Formats the date as YYYY-MM-DD. Jumps through hoops to avoid rolling back
  // or forward due to timezones: https://stackoverflow.com/a/29774197/6022817
  // Hurray for mature languages that are totally not amateurish jokes.
  function getDateString(d) {
    if (typeof d !== 'undefined') {
      d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
      return d.toISOString().slice(0, 10);
    } else {
      return '';
    }
  }

});
