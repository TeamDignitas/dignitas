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
          {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
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

      <div class="mt-4 text-end">
        {if $et->canDelete()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 me-2 mb-2"
            data-confirm="{t}info-confirm-delete-entity-type{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        <a href="{Router::link('entityType/list')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2">
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
          {include "bits/icon.tpl" i=save}
          {t}link-save{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
