{extends "layout.tpl"}

{block "title"}{t}title-edit-canned-response{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{t}title-edit-canned-response{/t}</h1>

    <form method="post">

      <div class="form-group">
        <label for="field-contents">{t}label-contents{/t}</label>
        <textarea
          id="field-contents"
          name="contents"
          class="form-control has-unload-warning size-limit easy-mde {if isset($errors.contents)}is-invalid{/if}"
          maxlength="{Comment::MAX_LENGTH}"
        >{$cannedResponse->contents|escape}</textarea>
        <div class="form-text d-flex justify-content-between">
          <small class="chars-remaining"></small>
          {include "bits/markdownHelp.tpl"}
        </div>
        {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
      </div>

      <div class="mt-4 text-end">
        {if $cannedResponse->id}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-3 col-lg-2 me-2 mb-2"
            data-confirm="{t}info-confirm-delete-canned-response{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        <a href="{Router::link('cannedResponse/list')}" class="btn btn-sm btn-outline-secondary col-sm-3 col-lg-2 me-2 mb-2">
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-3 col-lg-2 mb-2">
          {include "bits/icon.tpl" i=save}
          {t}link-save{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
