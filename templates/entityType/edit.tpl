{extends "layout.tpl"}

{block "title"}{t}title-edit-entity-type{/t}{/block}

{block "content"}
  <h2 class="mb-4">{t}title-edit-entity-type{/t}</h2>

  <form method="post">

    <div class="form-group">
      <label for="field-name" class="control-label">
        {t}label-name{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.name)}is-invalid{/if}"
          id="field-name"
          name="name"
          value="{$et->name|escape}"
          placeholder="{t}info-entity-type-name{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
      </div>
    </div>

    <div class="form-group form-check">
      <label class="form-check-label">
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

    <div class="form-group form-check">
      <label class="form-check-label">
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

    <div class="form-group form-check">
      <label class="form-check-label">
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

    <div class="mt-4">
      <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
        <i class="icon icon-floppy"></i>
        {t}link-save{/t}
      </button>

      <a href="{Router::link('entityType/list')}" class="btn btn-sm btn-outline-secondary">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>

      {if $et->canDelete()}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-sm btn-outline-danger float-right"
          data-confirm="{t}info-confirm-delete-entityType{/t}">
          <i class="icon icon-trash"></i>
          {t}link-delete{/t}
        </button>
      {/if}

    </div>
  </form>
{/block}
