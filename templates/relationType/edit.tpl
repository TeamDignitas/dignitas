{extends "layout.tpl"}

{block "title"}{t}title-edit-relation-type{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{t}title-edit-relation-type{/t}</h1>

    <form method="post">

      <div class="form-group">
        <label for="field-name" class="control-label">
          {t}label-name{/t}
        </label>
        <input type="text"
          class="form-control {if isset($errors.name)}is-invalid{/if}"
          id="field-name"
          name="name"
          value="{$rt->name|escape}"
          placeholder="{t}info-relation-type-name{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
      </div>

      <div class="form-group">
        <label for="field-from-entity-type-id" class="control-label">
          {t}label-from-entity-type{/t}
        </label>
        <select
          class="form-control"
          id="field-from-entity-type-id"
          name="fromEntityTypeId">
          {foreach $entityTypes as $et}
            <option
              value="{$et->id}"
              {if $rt->fromEntityTypeId == $et->id}selected{/if}>
              {$et->name}
            </option>
          {/foreach}
        </select>
      </div>

      <div class="form-group">
        <label for="field-to-entity-type-id" class="control-label">
          {t}label-to-entity-type{/t}
        </label>
        <select
          class="form-control"
          id="field-to-entity-type-id"
          name="toEntityTypeId">
          {foreach $entityTypes as $et}
            <option
              value="{$et->id}"
              {if $rt->toEntityTypeId == $et->id}selected{/if}>
              {$et->name}
            </option>
          {/foreach}
        </select>
      </div>

      <div class="form-group">
        <label for="field-weight" class="control-label">
          {t}label-weight{/t}
        </label>
        <input type="number"
          class="form-control {if isset($errors.weight)}is-invalid{/if}"
          id="field-weight"
          name="weight"
          value="{$rt->weight}"
          min="0.0"
          max="1.0"
          step="0.001"
          placeholder="{t}info-relation-type-weight{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.weight|default:null}
      </div>

      <div class="form-group form-check">
        <label class="form-check-label">
          <input
            type="checkbox"
            class="form-check-input {if isset($errors.symmetric)}is-invalid{/if}"
            name="symmetric"
            {if $rt->symmetric}checked{/if}
            value="1">
          {t}label-symmetric{/t}
        </label>
        <small class="form-text text-muted">
          {t}info-symmetric{/t}
        </small>
        {include "bits/fieldErrors.tpl" errors=$errors.symmetric|default:null}
      </div>

      <div class="form-group form-check">
        <label class="form-check-label">
          <input
            type="checkbox"
            class="form-check-input"
            name="membership"
            {if $rt->membership}checked{/if}
            value="1">
          {t}label-relation-type-membership{/t}
        </label>
        <small class="form-text text-muted">
          {t}info-relation-type-membership{/t}
        </small>
      </div>

      <div class="mt-4">
        <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>

        <a href="{Router::link('relationType/list')}" class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>

        {if $rt->canDelete()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger float-right"
            data-confirm="{t}info-confirm-delete-relation-type{/t}">
            <i class="icon icon-trash"></i>
            {t}link-delete{/t}
          </button>
        {/if}

      </div>
    </form>
  </div>
{/block}
