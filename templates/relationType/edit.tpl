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
          class="form-select"
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
          class="form-select"
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
        <label for="field-phrase" class="control-label">
          {t}label-phrase{/t}
        </label>
        <select
          class="form-select"
          id="field-phrase"
          name="phrase">
          {foreach RelationType::getPhrases() as $phrase}
            <option
              value="{$phrase}"
              {if $rt->phrase == $phrase}selected{/if}>
              {RelationType::phraseName($phrase)}
            </option>
          {/foreach}
        </select>
        <small class="form-text text-muted">
          {t}info-relation-type-phrase{/t}
        </small>
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

      {capture 'label'}{t}label-symmetric{/t}{/capture}
      {capture 'help'}{t}info-symmetric{/t}{/capture}
      {include 'bs/checkbox.tpl'
        cbErrors=$errors.symmetric|default:null
        checked=$rt->symmetric
        divClass='mb-3'
        help=$smarty.capture.help
        label=$smarty.capture.label
        name='symmetric'}

      {capture 'label'}{t}label-relation-type-membership{/t}{/capture}
      {capture 'help'}{t}info-relation-type-membership{/t}{/capture}
      {include 'bs/checkbox.tpl'
        checked=$rt->membership
        help=$smarty.capture.help
        label=$smarty.capture.label
        name='membership'}

      <div class="mt-4 text-end">
        {if $rt->canDelete()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-relation-type{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        {if $rt->id}
          <button
            name="cloneButton"
            type="submit"
            class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
            {include "bits/icon.tpl" i=content_copy}
            {t}link-clone{/t}
          </button>
        {/if}

        <a href="{Router::link('relationType/list')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
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
