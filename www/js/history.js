$(function() {

  function init() {
    $('.identicalToggle').click(toggleIdenticalLines);
  }

  function toggleIdenticalLines() {
    console.log('foo');
    $(this).siblings('pre').stop().toggle('fast');
  }

  init();
  
});
