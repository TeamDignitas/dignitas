{extends "layout.tpl"}

{block "title"}{t}title-edit-help-category{/t}{/block}

{block "content"}
  <h1 class="mb-5 capitalize-first-word">{t}title-edit-help-category{/t}</h1>

  <form method="post">

    <fieldset class="related-fields mb-5">
      <div class="form-group row py-1 pr-1">
        <label for="field-name" class="control-label col-2 mt-2">
          {t}label-name{/t}
        </label>
        <input type="text"
          class="form-control {if isset($errors.name)}is-invalid{/if} col-10"
          id="field-name"
          name="name"
          value="{$cat->name|escape}">
        {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
      </div>

      <div class="form-group row py-1 pr-1 mb-0">
        <label for="field-path" class="control-label col-2 mt-2">
          {t}label-help-category-path{/t}
        </label>
        <div class="col-10 px-0">
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
                <label class="icon icon-move"></label>
              </td>

              <td class="col-11">
                {$p->title}
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    </fieldset>

    <div class="mb-4">
      <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
        <i class="icon icon-floppy"></i>
        {t}link-save{/t}
      </button>

      <a href="{Router::helpLink($cat)}" class="btn btn-sm btn-outline-secondary">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>

      {if $canDelete}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-sm btn-outline-danger float-right"
          data-confirm="{t}info-confirm-delete-help-category{/t}">
          <i class="icon icon-trash"></i>
          {t}link-delete{/t}
        </button>
      {/if}

    </div>
  </form>
{/block}
