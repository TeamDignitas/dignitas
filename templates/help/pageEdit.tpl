{extends "layout.tpl"}

{block "title"}{t}title-edit-help-page{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5 capitalize-first-word">{t}title-edit-help-page{/t}</h1>

    <form method="post">

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="field-title" class="control-label col-sm-12 col-lg-2 mt-2 pl-0">
            {t}label-title{/t}
          </label>
          <input type="text"
            class="form-control {if isset($errors.title)}is-invalid{/if} col-sm-12 col-lg-10"
            id="field-title"
            name="title"
            value="{$page->title|escape}">
          {include "bits/fieldErrors.tpl" errors=$errors.title|default:null}
        </div>

        <div class="form-group row">
          <label for="field-path" class="control-label col-sm-12 col-lg-2 mt-2 pl-0">
            {t}label-help-page-path{/t}
          </label>
          <input type="text"
            class="form-control {if isset($errors.path)}is-invalid{/if} col-sm-12 col-lg-10"
            id="field-path"
            name="path"
            value="{$page->path|escape}"
            placeholder="{t}info-help-page-path{/t}">
          {include "bits/fieldErrors.tpl" errors=$errors.path|default:null}
        </div>

        <div class="form-group row">
          <label for="field-category-id" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-category{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
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

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="field-contents" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-contents{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
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

        <a href="{$page->getViewUrl()}" class="btn btn-sm btn-outline-secondary">
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
  </div>
{/block}
