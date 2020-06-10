{extends "layout.tpl"}

{block "title"}{t}title-edit-static-resource{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-edit-static-resource{/t}</h1>

    <form method="post" enctype="multipart/form-data">

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
              value="{$sr->name|escape}">
            {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
            <small class="form-text text-muted">
              {t}info-static-resource-name{/t}
            </small>
          </div>
        </div>

        <div class="form-group row">
          <label for="field-locale" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-locale{/t}</label>
          <select
            name="locale"
            id="field-locale"
            class="form-control col-sm-12 col-lg-10">
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
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="field-contents" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-contents{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
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
          </div>
        </div>
      </fieldset>

      <div class="mt-4">
        <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>

        <a href="{Router::link('staticResource/list')}" class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>

        {if $sr->id}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger float-right"
            data-confirm="{t}info-confirm-delete-static-resource{/t}">
            <i class="icon icon-trash"></i>
            {t}link-delete{/t}
          </button>
        {/if}

      </div>
    </form>
  </div>
{/block}
