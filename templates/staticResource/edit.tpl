{extends "layout.tpl"}

{block "title"}{t}title-edit-static-resource{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-edit-static-resource{/t}</h1>

    <form method="post" enctype="multipart/form-data">

      <fieldset class="related-fields mb-5 ml-3">
        {hf inputId='field-name' label="{t}label-name{/t}"}
          <input type="text"
            class="form-control {if isset($errors.name)}is-invalid{/if}"
            id="field-name"
            name="name"
            value="{$sr->name|escape}">
          {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
          <small class="form-text">
            {t}info-static-resource-name{/t}
          </small>
        {/hf}

        {hf inputId='field-locale' label="{t}label-locale{/t}"}
          <select
            name="locale"
            id="field-locale"
            class="form-select">
            <option value="">
              {t}label-all-locales{/t}
            </option>
            {foreach Config::LOCALES as $code => $name}
              <option
                value="{$code}"
                {if $sr->locale == $code}selected{/if}>
                {$name|escape}
              </option>
            {/foreach}
          </select>
        {/hf}
      </fieldset>

      <fieldset class="related-fields mb-5 ml-3">
        {hf inputId='field-contents' label="{t}label-contents{/t}"}
          {strip}
          <textarea
            id="field-contents"
            name="contents"
            class="form-control has-unload-warning size-limit easy-mde"
            data-mime="{$sr->getMimeType()}"
            rows="10">{$sr->getEditableContents()|escape}</textarea>
          {/strip}

          <div class="custom-file mb-2">
            <input
              name="file"
              type="file"
              class="custom-file-input {if isset($errors.file)}is-invalid{/if}"
              id="field-file">

            <label class="custom-file-label mt-2" for="field-file">
              {t}info-choose-static-resource-file{/t}
            </label>
          </div>
          {include "bits/fieldErrors.tpl" errors=$errors.file|default:null}
        {/hf}
      </fieldset>

      <div class="mt-4 text-end">
        {if $sr->id}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-static-resource{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        <a href="{Router::link('staticResource/list')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
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
