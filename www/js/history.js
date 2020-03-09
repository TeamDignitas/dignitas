$(function() {

  function init() {
    $('.identical-toggle').click(toggleIdenticalLines);
  }

  function toggleIdenticalLines() {
    console.log('foo');
    $(this).siblings('pre').stop().toggle('fast');
  }

  init();

});
