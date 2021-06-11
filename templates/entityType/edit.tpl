{extends "layout.tpl"}

{block "title"}{t}title-edit-entity-type{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-edit-entity-type{/t}</h1>

    <form method="post">

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="field-name" class="control-label col-sm-12 col-lg-2 mt-2 pl-0">
            {t}label-name{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <input type="text"
              class="form-control {if isset($errors.name)}is-invalid{/if}"
              id="field-name"
              name="name"
              value="{$et->name|escape}"
              placeholder="{t}info-entity-type-name{/t}">
            {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
          </div>
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5">
        {capture 'label'}{t}label-loyalty-source{/t}{/capture}
        {capture 'help'}{t}info-loyalty-source{/t}{/capture}
        {include 'bs/checkbox.tpl'
          checked=$et->loyaltySource
          divClass='mb-3'
          help=$smarty.capture.help
          label=$smarty.capture.label
          name='loyaltySource'}

        {capture 'label'}{t}label-loyalty-sink{/t}{/capture}
        {capture 'help'}{t}info-loyalty-sink{/t}{/capture}
        {include 'bs/checkbox.tpl'
          checked=$et->loyaltySink
          divClass='mb-3'
          help=$smarty.capture.help
          label=$smarty.capture.label
          name='loyaltySink'}

        {capture 'label'}{t}label-has-color{/t}{/capture}
        {capture 'help'}{t}info-has-color{/t}{/capture}
        {include 'bs/checkbox.tpl'
          checked=$et->hasColor
          divClass='mb-3'
          help=$smarty.capture.help
          label=$smarty.capture.label
          name='hasColor'}

        {capture 'label'}{t}label-is-default{/t}{/capture}
        {capture 'help'}{t}info-is-default{/t}{/capture}
        {include 'bs/checkbox.tpl'
          checked=$et->isDefault
          divClass='mb-3'
          help=$smarty.capture.help
          label=$smarty.capture.label
          name='isDefault'}
      </fieldset>

      <div class="mt-4 text-end">
        {if $et->canDelete()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-entity-type{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        <a href="{Router::link('entityType/list')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
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
