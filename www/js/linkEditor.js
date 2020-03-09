$(function() {
  var stem;

  function init() {
    stem = $('#linkStem').detach().removeAttr('hidden id');

    $('button.add-link').click(addLink);
    $('#linkContainer').on('click', 'button.delete-link', deleteLink);
  }

  function addLink() {
    var t = stem.clone(true).appendTo('#linkContainer');
  }

  function deleteLink() {
    $(this).closest('tr').remove();
  }

  init();

});
