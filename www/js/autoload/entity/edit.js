$(function() {

  const TO_ENTITY_ID_OPTIONS = {
    ajax: {
      url: URL_PREFIX + 'ajax/search-entities',
    },
    dropdownCssClass: "select2--small",
    minimumInputLength: 2,
    selectionCssClass: "select2--small",
    width: '100%',
  };

  var stemAlias = null;
  var stemRelation = null;

  function init() {
    stemAlias = $('#stem-alias').detach().removeAttr('hidden id');
    stemRelation = $('#stem-relation').detach().removeAttr('hidden id');

    initSelect2('.to-entity-id', URL_PREFIX + 'ajax/load-entities', TO_ENTITY_ID_OPTIONS);

    $('button#add-alias').click(addAlias);
    $('button#add-relation').click(addRelation);

    $('#alias-container, #relation-container')
      .on('click', 'button.delete-dependant', deleteDependant);

    var sel = $('#field-entity-type-id');
    sel.data('lastSelected', sel.val());
    sel.click(function() {
      sel.data('lastSelected', sel.val());
    });
    sel.change(entityTypeChange);
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

  function entityTypeChange() {
    // warn about effects of changing the entity type
    if (!confirm($(this).data('changeMsg'))) {
      $(this).val($(this).data('lastSelected'));
      return false;
    }

    // remove relations
    $('#relation-container tr').remove();

    // disable the add relation button
    $('#add-relation').prop('disabled', true);

    // update color visibility
    var sel = $('option:selected', this);
    $('#color-wrapper').prop('hidden', !sel.data('has-color'));
  }

  init();

});
