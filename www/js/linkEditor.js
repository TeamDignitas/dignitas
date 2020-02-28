$(function() {
  var stem;

  function init() {
    stem = $('#linkStem').detach().removeAttr('hidden id');

    $('.addLinkButton').click(addLink);
    $('#linkContainer').on('click', '.deleteLinkButton', deleteLink);
  }

  function addLink() {
    var t = stem.clone(true).appendTo('#linkContainer');
  }

  function deleteLink() {
    $(this).closest('tr').remove();
  }

  init();

});
