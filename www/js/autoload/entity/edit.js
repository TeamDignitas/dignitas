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
    stemAlias = $('#stem-alias').detach().removeAttr('hidden');
    stemRelation = $('#stem-relation').detach().removeAttr('hidden');

    initSelect2('.to-entity-id', URL_PREFIX + 'ajax/load-entities', TO_ENTITY_ID_OPTIONS);

    $('button#add-alias').click(addAlias);
    $('button#add-relation').click(addRelation);

    $('#alias-container, #relation-container')
      .on('click', 'button.delete-dependant', deleteDependant);

    $('.colorpicker-component').colorpicker({
      fallbackColor: '#ffffff',
    });
    $('#field-type').change(updateColorVisibility);
  }

  function addAlias() {
    var t = stemAlias.clone(true).appendTo('#alias-container');
    $('#alias-header').removeAttr('hidden');
  }

  function addRelation() {
    var t = stemRelation.clone(true).appendTo('#relation-container');
    t.find('.to-entity-id').select2(TO_ENTITY_ID_OPTIONS);
    $('#relation-header').removeAttr('hidden');
  }

  function deleteDependant() {
    $(this).closest('tr').remove();
  }

  function updateColorVisibility() {
    var sel = $('option:selected', this);
    $('#color-wrapper').prop('hidden', !sel.data('has-color'));
  }

  init();

});
