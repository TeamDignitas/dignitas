$(function() {
  var stem = null; // stem source

  function init() {
    stem = $('#stem').detach().removeAttr('hidden');

    $('#addSourceButton').click(addSource);
    $('#sourceContainer').on('click', '.deleteSourceButton', deleteSource);

    Sortable.create(sourceContainer, {
      handle: '.icon-move',
	    animation: 150,
    });

    initSimpleMde('fieldContext');

    initSelect2('#fieldEntityId', URL_PREFIX + 'ajax/load-entities', {
      ajax: {
        url: URL_PREFIX + 'ajax/search-entities',
      },
      minimumInputLength: 2,
    });
  }

  function addSource() {
    var t = stem.clone(true).appendTo('#sourceContainer');
  }

  function deleteSource() {
    $(this).closest('tr').remove();
  }

  init();

});
