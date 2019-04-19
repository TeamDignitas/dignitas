$(function() {

  const TO_ENTITY_ID_OPTIONS = {
    ajax: {
      url: URL_PREFIX + 'ajax/search-entities',
    },
    minimumInputLength: 2,
    width: '100%',
  };

  var stem = null; // stem relation

  function init() {
    stem = $('#stem').detach();

    initSelect2('.toEntityId', URL_PREFIX + 'ajax/load-entities', TO_ENTITY_ID_OPTIONS);

    $('#addRelationButton').click(addRelation);
    $('#relationContainer').on('click', '.deleteRelationButton', deleteRelation);

    Sortable.create(relationContainer, {
      handle: '.icon-move',
	    animation: 150,
    });

  }

  function addRelation() {
    var t = stem.clone(true).appendTo('#relationContainer');
    t.find('.toEntityId').select2(TO_ENTITY_ID_OPTIONS);
    $('#relationHeader').removeAttr('hidden');
  }

  function deleteRelation() {
    $(this).closest('tr').remove();
  }

  init();

});
