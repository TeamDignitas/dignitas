{extends "layout.tpl"}

{block "title"}{t}title-edit-domain{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-edit-domain{/t}</h1>

    <form method="post" enctype="multipart/form-data">

      <fieldset class="related-fields mb-5 ml-3">
        {hf inputId='field-name' label="{t}label-name{/t}"}
          <input type="text"
            class="form-control {if isset($errors.name)}is-invalid{/if}"
            id="field-name"
            name="name"
            value="{$domain->name|escape}"
            placeholder="{t}info-domain-name{/t}">
          {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
        {/hf}

        {hf inputId='field-display-value' label="{t}label-display-value{/t}"}
          <input type="text"
            class="form-control {if isset($errors.displayValue)}is-invalid{/if}"
            id="field-display-value"
            name="displayValue"
            value="{$domain->displayValue|escape}"
            placeholder="{t}info-domain-display-value{/t}">
          {include "bits/fieldErrors.tpl" errors=$errors.displayValue|default:null}
        {/hf}

        {hf inputId='field-image' label="{t}label-image{/t}"}
          <div>
            {include "bits/image.tpl"
              obj=$domain
              geometry=Config::THUMB_DOMAIN}
          </div>

          <div class="custom-file">
            <input
              name="image"
              type="file"
              class="custom-file-input {if isset($errors.image)}is-invalid{/if}"
              id="field-image">
            <label class="custom-file-label" for="field-image">
              {t}info-upload-image{/t}
            </label>
          </div>
          {include "bits/fieldErrors.tpl" errors=$errors.image|default:null}

          {include 'bs/checkbox.tpl'
            divClass='mt-1'
            label="{t}label-delete-image{/t}"
            name='deleteImage'}
        {/hf}
      </fieldset>

      <div class="mt-4 text-end">
        {if $canDelete}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-domain{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        {if $domain->id}
          <button name="cloneButton" type="submit" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
            {include "bits/icon.tpl" i=content_copy}
            {t}link-clone{/t}
          </button>
        {/if}

        <a href="{Router::link('domain/list')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
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
