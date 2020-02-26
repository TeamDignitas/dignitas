{extends "layout.tpl"}

{block "title"}{t}title-edit-help-category{/t}{/block}

{block "content"}
  <h3>{t}title-edit-help-category{/t}</h3>

  <form method="post">

    <div class="form-group">
      <label for="fieldName" class="control-label">
        {t}label-name{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.name)}is-invalid{/if}"
          id="fieldName"
          name="name"
          value="{$cat->name|escape}">
        {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
      </div>
    </div>

    <div class="form-group">
      <label for="fieldPath" class="control-label">
        {t}label-help-category-path{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.path)}is-invalid{/if}"
          id="fieldPath"
          name="path"
          value="{$cat->path|escape}"
          placeholder="{t}info-help-category-path{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.path|default:null}
      </div>
    </div>

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}link-save{/t}
      </button>

      <a href="{Router::link('help/categoryList')}" class="btn btn-light">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>

      {if $canDelete}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-danger float-right"
          data-confirm="{t}info-confirm-delete-help-category{/t}">
          <i class="icon icon-trash"></i>
          {t}link-delete{/t}
        </button>
      {/if}

    </div>
  </form>
{/block}
