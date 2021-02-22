{extends "layout.tpl"}

{block "title"}{t}title-edit-help-category{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5 capitalize-first-word">{t}title-edit-help-category{/t}</h1>

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
              value="{$cat->name|escape}">
            {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
          </div>
        </div>

        <div class="form-group row">
          <label for="field-path" class="control-label col-sm-12 col-lg-2 mt-2 pl-0">
            {t}label-help-category-path{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <input type="text"
              class="form-control {if isset($errors.path)}is-invalid{/if}"
              id="field-path"
              name="path"
              value="{$cat->path|escape}"
              placeholder="{t}info-help-category-path{/t}">
            {include "bits/fieldErrors.tpl" errors=$errors.path|default:null}
          </div>
        </div>
      </fieldset>

      <fieldset class="mt-5">
        <legend>{cap}{t}title-help-pages-in-category{/t}{/cap}</legend>

        <table class="table table-hover mt-3 sortable">
          <tbody>
            {foreach $cat->getPages() as $p}
              <tr class="d-flex">
                <td class="col-1">
                  <input type="hidden" name="pageIds[]" value="{$p->id}">
                  {include "bits/icon.tpl" i=drag_indicator class="drag-indicator pt-0"}
                </td>

                <td class="col-11">
                  {$p->title}
                </td>
              </tr>
            {/foreach}
          </tbody>
        </table>
      </fieldset>

      <div class="mb-4 text-right">
        {if $canDelete}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-12 col-md-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-help-category{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        <a href="{$cat->getViewUrl()}" class="btn btn-sm btn-outline-secondary col-sm-12 col-md-2 mr-2 mb-2">
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-12 col-md-2 mb-2">
          {include "bits/icon.tpl" i=save}
          {t}link-save{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
