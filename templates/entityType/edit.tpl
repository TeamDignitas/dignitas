{extends "layout.tpl"}

{block "title"}{t}title-edit-entity-type{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-edit-entity-type{/t}</h1>

    <form method="post">

      <fieldset class="related-fields mb-5 ms-3">
        {hf inputId='field-name' label="{t}label-name{/t}"}
          <input type="text"
            class="form-control {if isset($errors.name)}is-invalid{/if}"
            id="field-name"
            name="name"
            value="{$et->name|escape}"
            placeholder="{t}info-entity-type-name{/t}">
          {include "bs/feedback.tpl" errors=$errors.name|default:null}
        {/hf}
      </fieldset>

      <fieldset class="related-fields mb-5">
        {include 'bs/checkbox.tpl'
          checked=$et->loyaltySource
          divClass='mb-3'
          help="{t}info-loyalty-source{/t}"
          label="{t}label-loyalty-source{/t}"
          name='loyaltySource'}

        {include 'bs/checkbox.tpl'
          cbErrors=$errors.loyaltySink|default:null
          checked=$et->loyaltySink
          divClass='mb-3'
          help="{t}info-loyalty-sink{/t}"
          label="{t}label-loyalty-sink{/t}"
          name='loyaltySink'}

        {include 'bs/checkbox.tpl'
          checked=$et->hasColor
          divClass='mb-3'
          help="{t}info-has-color{/t}"
          label="{t}label-has-color{/t}"
          name='hasColor'}

        {include 'bs/checkbox.tpl'
          checked=$et->isDefault
          divClass='mb-3'
          help="{t}info-is-default{/t}"
          label="{t}label-is-default{/t}"
          name='isDefault'}
      </fieldset>

      {include "bs/actions.tpl"
        cancelLink=Router::link('entityType/list')
        deleteButton=$et->canDelete()
        deleteButtonConfirm="{t}info-confirm-delete-entity-type{/t}"}

    </form>
  </div>
{/block}
