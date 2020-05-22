{extends "layout.tpl"}

{block "title"}{t}title-edit-domain{/t}{/block}

{block "content"}
  <div class="container mt-5">
    <h1 class="mb-5">{t}title-edit-domain{/t}</h1>

    <form method="post" enctype="multipart/form-data">

      <fieldset class="related-fields mb-5">
        <div class="form-group row py-1 pr-1">
          <label for="field-name" class="control-label col-2 ml-0 mt-2">
            {t}label-name{/t}
          </label>
          <input type="text"
            class="form-control {if isset($errors.name)}is-invalid{/if} col-10"
            id="field-name"
            name="name"
            value="{$domain->name|escape}"
            placeholder="{t}info-domain-name{/t}">
          {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
        </div>

        <div class="form-group row py-1 pr-1">
          <label for="field-display-value" class="control-label col-2 ml-0 mt-2">
            {t}label-display-value{/t}
          </label>
          <input type="text"
            class="form-control {if isset($errors.displayValue)}is-invalid{/if} col-10"
            id="field-display-value"
            name="displayValue"
            value="{$domain->displayValue|escape}"
            placeholder="{t}info-domain-display-value{/t}">
          {include "bits/fieldErrors.tpl" errors=$errors.displayValue|default:null}
        </div>

        <div class="form-group row py-1 pr-1 mb-0">
          <label for="field-image" class="col-2 ml-0 mt-2">{t}label-image{/t}</label>

          <div class="col-10 px-0">
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

            <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" name="deleteImage" class="form-check-input">
                {t}label-delete-image{/t}
              </label>
            </div>
          </div>
        </div>
      </fieldset>

      <div class="mt-4">
        <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>

        {if $domain->id}
          <button name="cloneButton" type="submit" class="btn btn-sm btn-outline-secondary">
            <i class="icon icon-clone"></i>
            {t}link-clone{/t}
          </button>
        {/if}

        <a href="{Router::link('domain/list')}" class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>

        {if $canDelete}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger float-right"
            data-confirm="{t}info-confirm-delete-domain{/t}">
            <i class="icon icon-trash"></i>
            {t}link-delete{/t}
          </button>
        {/if}

      </div>
    </form>
  </div>
{/block}
