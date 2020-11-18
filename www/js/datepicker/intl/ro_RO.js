$(function(){
  $.fn.datepickerOptions = {
    months: [
      'necunoscută',
      'ianuarie', 'februarie', 'martie', 'aprilie',
      'mai', 'iunie', 'iulie', 'august', 'septembrie',
      'octombrie', 'noiembrie', 'decembrie',
    ],

    labels: {
      title: 'alegeți o dată',

      year: 'anul',
      month: 'luna',
      day: 'ziua',

      accept: 'acceptă',
      clear: 'șterge',
      today: 'astăzi',
    },

    format: function(y, m, d) {
      return d + ' ' + this.months[m] + ' ' + y;
    },
  };
});
