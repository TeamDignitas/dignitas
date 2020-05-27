$(function() {

  function init() {
    var hash = window.location.hash;
    if (hash) {
      $(hash).addClass('highlighted');
    }
  }

  init();

});
