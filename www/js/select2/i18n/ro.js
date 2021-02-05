/**
 * Romanian translation, modified to remove the T-V distinction.
 **/
$.fn.select2.amd.define('select2/i18n/ro', [], function () {
  return {
    errorLoading: function () {
      return 'Rezultatele nu au putut fi încărcate.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Șterge ' + overChars + ' caracter';

      if (overChars !== 1) {
        message += 'e';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Introdu ' + remainingChars +
        ' sau mai multe caractere';

      return message;
    },
    loadingMore: function () {
      return 'Se încarcă mai multe rezultate…';
    },
    maximumSelected: function (args) {
      var message = 'Poți selecta cel mult ' + args.maximum;
      message += ' element';

      if (args.maximum !== 1) {
        message += 'e';
      }

      return message;
    },
    noResults: function () {
      return 'Nu au fost găsite rezultate';
    },
    searching: function () {
      return 'Căutare…';
    },
    removeAllItems: function () {
      return 'Elimină toate elementele';
    }
  };
});
