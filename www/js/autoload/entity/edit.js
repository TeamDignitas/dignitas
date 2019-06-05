$(function() {

  const TO_ENTITY_ID_OPTIONS = {
    ajax: {
      url: URL_PREFIX + 'ajax/search-entities',
    },
    minimumInputLength: 2,
    width: '100%',
  };

  var stemAlias = null;
  var stemRelation = null;

  function init() {
    stemAlias = $('#stemAlias').detach().removeAttr('hidden');
    stemRelation = $('#stemRelation').detach().removeAttr('hidden');

    initSelect2('.toEntityId', URL_PREFIX + 'ajax/load-entities', TO_ENTITY_ID_OPTIONS);

    $('#addAliasButton').click(addAlias);
    $('#addRelationButton').click(addRelation);

    $('#relationContainer, #aliasContainer').on('click', '.deleteDependantButton', deleteDependant);

    var sortableOpts = {
      handle: '.icon-move',
	    animation: 150,
    };

    Sortable.create(aliasContainer, sortableOpts);
    Sortable.create(relationContainer, sortableOpts);
  }

  function addAlias() {
    var t = stemAlias.clone(true).appendTo('#aliasContainer');
    $('#aliasHeader').removeAttr('hidden');
  }

  function addRelation() {
    var t = stemRelation.clone(true).appendTo('#relationContainer');
    t.find('.toEntityId').select2(TO_ENTITY_ID_OPTIONS);
    $('#relationHeader').removeAttr('hidden');
  }

  function deleteDependant() {
    $(this).closest('tr').remove();
  }

  init();

});
