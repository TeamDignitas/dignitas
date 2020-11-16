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
          <input type="text"
            class="form-control {if isset($errors.name)}is-invalid{/if} col-sm-12 col-lg-10"
            id="field-name"
            name="name"
            value="{$et->name|escape}"
            placeholder="{t}info-entity-type-name{/t}">
          {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5">
        <div class="form-group form-check row">
          <label class="form-check-label col-12">
            <input
              type="checkbox"
              class="form-check-input"
              name="loyaltySource"
              {if $et->loyaltySource}checked{/if}
              value="1">
            {t}label-loyalty-source{/t}
          </label>
          <small class="form-text text-muted">
            {t}info-loyalty-source{/t}
          </small>
        </div>

        <div class="form-group form-check row">
          <label class="form-check-label col-12">
            <input
              type="checkbox"
              class="form-check-input {if isset($errors.loyaltySink)}is-invalid{/if}"
              name="loyaltySink"
              {if $et->loyaltySink}checked{/if}
              value="1">
            {t}label-loyalty-sink{/t}
          </label>
          <small class="form-text text-muted">
            {t}info-loyalty-sink{/t}
          </small>
          {include "bits/fieldErrors.tpl" errors=$errors.loyaltySink|default:null}
        </div>

        <div class="form-group form-check row">
          <label class="form-check-label col-12">
            <input
              type="checkbox"
              class="form-check-input"
              name="hasColor"
              {if $et->hasColor}checked{/if}
              value="1">
            {t}label-has-color{/t}
          </label>
          <small class="form-text text-muted">
            {t}info-has-color{/t}
          </small>
        </div>

        <div class="form-group form-check row">
          <label class="form-check-label col-12">
            <input
              type="checkbox"
              class="form-check-input"
              name="isDefault"
              {if $et->isDefault}checked{/if}
              value="1">
            {t}label-is-default{/t}
          </label>
          <small class="form-text text-muted">
            {t}info-is-default{/t}
          </small>
        </div>
      </fieldset>

      <div class="mt-4 text-right">
        {if $et->canDelete()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-entity-type{/t}">
            <i class="icon icon-trash"></i>
            {t}link-delete{/t}
          </button>
        {/if}

        <a href="{Router::link('entityType/list')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
