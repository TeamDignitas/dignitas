$(function() {

  function init() {
    $('.identical-toggle').click(toggleIdenticalLines);
  }

  function toggleIdenticalLines() {
    $(this).siblings('pre').stop().toggle('fast');
  }

  init();

});
