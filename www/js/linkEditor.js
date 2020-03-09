$(function() {
  var stem;

  function init() {
    stem = $('#linkStem').detach().removeAttr('hidden id');

    $('button.add-link').click(addLink);
    $('#link-container').on('click', 'button.delete-link', deleteLink);
  }

  function addLink() {
    var t = stem.clone(true).appendTo('#link-container');
  }

  function deleteLink() {
    $(this).closest('tr').remove();
  }

  init();

});
