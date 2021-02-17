$(function() {
  var menuBar = null;
  var stemLi = null;
  var sel = null; // selected <li>

  function init() {
    $('.expand').click(toggleSubtree);

    // collapse all subtrees
    $('#tag-tree ul ul').hide();
  }

  function toggleSubtree() {
    $(this).siblings('ul').stop().slideToggle();
    $(this).toggleClass('expanded');
  }

  init();
});
