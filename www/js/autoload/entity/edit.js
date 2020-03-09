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

    initSelect2('.to-entity-id', URL_PREFIX + 'ajax/load-entities', TO_ENTITY_ID_OPTIONS);

    $('#addAliasButton').click(addAlias);
    $('#addRelationButton').click(addRelation);

    $('#aliasContainer, #relationContainer')
      .on('click', 'button.delete-dependant', deleteDependant);

    $('.colorpicker-component').colorpicker({
      fallbackColor: '#ffffff',
    });
    $('#fieldType').change(updateColorVisibility);
  }

  function addAlias() {
    var t = stemAlias.clone(true).appendTo('#aliasContainer');
    $('#aliasHeader').removeAttr('hidden');
  }

  function addRelation() {
    var t = stemRelation.clone(true).appendTo('#relationContainer');
    t.find('.to-entity-id').select2(TO_ENTITY_ID_OPTIONS);
    $('#relationHeader').removeAttr('hidden');
  }

  function deleteDependant() {
    $(this).closest('tr').remove();
  }

  function updateColorVisibility() {
    var sel = $('option:selected', this);
    $('#colorFieldWrapper').prop('hidden', !sel.data('has-color'));
  }

  init();

});
