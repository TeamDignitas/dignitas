{extends "layout.tpl"}

{block "title"}{t}title-edit-domain{/t}{/block}

{block "content"}
  <h3>{t}title-edit-domain{/t}</h3>

  <form method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label for="fieldName" class="control-label">
        {t}label-name{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.name)}is-invalid{/if}"
          id="fieldName"
          name="name"
          value="{$domain->name|escape}"
          placeholder="{t}info-domain-name{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
      </div>
    </div>

    <div class="form-group">
      <label for="fieldDisplayValue" class="control-label">
        {t}label-display-value{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.displayValue)}is-invalid{/if}"
          id="fieldDisplayValue"
          name="displayValue"
          value="{$domain->displayValue|escape}"
          placeholder="{t}info-domain-display-value{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.displayValue|default:null}
      </div>
    </div>

    <div class="form-group">
      <label for="fieldImage">{t}label-image{/t}</label>

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
          id="fieldImage">
        <label class="custom-file-label" for="fieldImage">
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

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}link-save{/t}
      </button>

      {if $domain->id}
        <button name="cloneButton" type="submit" class="btn btn-light">
          <i class="icon icon-clone"></i>
          {t}link-clone{/t}
        </button>
      {/if}

      <a href="{Router::link('domain/list')}" class="btn btn-light">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>

      {if $canDelete}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-danger float-right"
          data-confirm="{t}info-confirm-delete-domain{/t}">
          <i class="icon icon-trash"></i>
          {t}link-delete{/t}
        </button>
      {/if}

    </div>
  </form>
{/block}
