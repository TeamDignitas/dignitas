$(function() {
  var stem;

  function init() {
    stem = $('#urlStem').detach().removeAttr('hidden');

    $('.addUrlButton').click(addUrl);
    $('#urlContainer').on('click', '.deleteUrlButton', deleteUrl);

    Sortable.create(urlContainer, {
      handle: '.icon-move',
	    animation: 150,
    });
  }

  function addUrl() {
    var t = stem.clone(true).appendTo('#urlContainer');
  }

  function deleteUrl() {
    $(this).closest('tr').remove();
  }

  init();

});
