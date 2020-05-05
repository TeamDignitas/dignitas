{extends "layout.tpl"}

{block "title"}{t}title-edit-help-page{/t}{/block}

{block "content"}
  <h1 class="mb-5 capitalize-first-word">{t}title-edit-help-page{/t}</h1>

  <form method="post">

    <fieldset class="related-fields mb-5">
      <div class="form-group row py-1 pr-1">
        <label for="field-title" class="control-label col-2 mt-2">
          {t}label-title{/t}
        </label>
        <input type="text"
          class="form-control {if isset($errors.title)}is-invalid{/if} col-10"
          id="field-title"
          name="title"
          value="{$page->title|escape}">
        {include "bits/fieldErrors.tpl" errors=$errors.title|default:null}
      </div>

      <div class="form-group row py-1 pr-1">
        <label for="field-path" class="control-label col-2 mt-2">
          {t}label-help-page-path{/t}
        </label>
        <input type="text"
          class="form-control {if isset($errors.path)}is-invalid{/if} col-10"
          id="field-path"
          name="path"
          value="{$page->path|escape}"
          placeholder="{t}info-help-page-path{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.path|default:null}
      </div>

      <div class="form-group row py-1 pr-1 mb-0">
        <label for="field-category-id" class="col-2 mt-2">{t}label-category{/t}</label>
        <div class="col-10 px-0">
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
      </div>
    </fieldset>

    <fieldset class="related-fields mb-5">
      <div class="form-group row py-1 pr-1 mb-0">
        <label for="field-contents" class="col-2 mt-2">{t}label-contents{/t}</label>
        <div class="col-10 px-0">
          <textarea
            id="field-contents"
            class="form-control has-unload-warning easy-mde"
            name="contents">{$page->contents|escape}</textarea>
          {include "bits/markdownHelp.tpl"}
          {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
        </div>
      </div>
    </fieldset>

    <div class="mt-4">
      <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
        <i class="icon icon-floppy"></i>
        {t}link-save{/t}
      </button>

      <a href="{Router::helpLink($page)}" class="btn btn-sm btn-outline-secondary">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>

      {if $page->id}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-sm btn-outline-danger float-right"
          data-confirm="{t}info-confirm-delete-help-page{/t}">
          <i class="icon icon-trash"></i>
          {t}link-delete{/t}
        </button>
      {/if}
    </div>
  </form>
{/block}
