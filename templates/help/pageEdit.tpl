{extends "layout.tpl"}

{block "title"}{t}title-edit-help-page{/t}{/block}

{block "content"}
  <h3>{t}title-edit-help-page{/t}</h3>

  <form method="post">

    <div class="form-group">
      <label for="field-title" class="control-label">
        {t}label-title{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.title)}is-invalid{/if}"
          id="field-title"
          name="title"
          value="{$page->title|escape}">
        {include "bits/fieldErrors.tpl" errors=$errors.title|default:null}
      </div>
    </div>

    <div class="form-group">
      <label for="field-path" class="control-label">
        {t}label-help-page-path{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.path)}is-invalid{/if}"
          id="field-path"
          name="path"
          value="{$page->path|escape}"
          placeholder="{t}info-help-page-path{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.path|default:null}
      </div>
    </div>

    <div class="form-group">
      <label for="field-category-id">{t}label-category{/t}</label>
      <select
        name="categoryId"
        id="field-category-id"
        class="form-control {if isset($errors.categoryId)}is-invalid{/if}">
        {foreach HelpCategory::loadAll() as $cat}
          <option
            value="{$cat->id}"
            {if $cat->id == $page->categoryId}selected{/if}>
            {$cat->name}
          </option>
        {/foreach}
      </select>
      {include "bits/fieldErrors.tpl" errors=$errors.categoryId|default:null}
    </div>

    <div class="form-group">
      <label for="field-contents">{t}label-contents{/t}</label>
      <textarea
        id="field-contents"
        class="form-control has-unload-warning easy-mde"
        name="contents">{$page->contents|escape}</textarea>
      {include "bits/markdownHelp.tpl"}
      {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
    </div>

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}link-save{/t}
      </button>

      <a href="{Router::helpLink($page)}" class="btn btn-light">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>

      {if $page->id}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-danger float-right"
          data-confirm="{t}info-confirm-delete-help-page{/t}">
          <i class="icon icon-trash"></i>
          {t}link-delete{/t}
        </button>
      {/if}
    </div>
  </form>
{/block}
