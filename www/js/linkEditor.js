$(function() {
  var stem;

  function init() {
    stem = $('#linkStem').detach().removeAttr('hidden');

    $('.addLinkButton').click(addLink);
    $('#linkContainer').on('click', '.deleteLinkButton', deleteLink);

    Sortable.create(linkContainer, {
      handle: '.icon-move',
	    animation: 150,
    });
  }

  function addLink() {
    var t = stem.clone(true).appendTo('#linkContainer');
  }

  function deleteLink() {
    $(this).closest('tr').remove();
  }

  init();

});
