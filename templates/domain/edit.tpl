{extends "layout.tpl"}

{block "title"}{t}edit domain{/t}{/block}

{block "content"}
  <h3>{t}edit domain{/t}</h3>

  <form method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label for="fieldName" class="control-label">
        {t}name{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.name)}is-invalid{/if}"
          id="fieldName"
          name="name"
          value="{$domain->name|escape}"
          placeholder="{t}a domain name like google.com{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
      </div>
    </div>

    <div class="form-group">
      <label for="fieldDisplayValue" class="control-label">
        {t}display value{/t}
      </label>
      <div>
        <input type="text"
          class="form-control {if isset($errors.displayValue)}is-invalid{/if}"
          id="fieldDisplayValue"
          name="displayValue"
          value="{$domain->displayValue|escape}"
          placeholder="{t}a value more suitable for humans{/t}">
        {include "bits/fieldErrors.tpl" errors=$errors.displayValue|default:null}
      </div>
    </div>

    <div class="form-group">
      <label for="fieldImage">{t}image{/t}</label>

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
          {t}choose an image to upload or leave empty to keep existing image{/t}
        </label>
      </div>
      {include "bits/fieldErrors.tpl" errors=$errors.image|default:null}

      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" name="deleteImage" class="form-check-input">
          {t}delete image{/t}
        </label>
      </div>
    </div>

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}save{/t}
      </button>

      {if $domain->id}
        <button name="cloneButton" type="submit" class="btn btn-light">
          <i class="icon icon-clone"></i>
          {t}clone{/t}
        </button>
      {/if}

      <a href="{Router::link('domain/list')}" class="btn btn-light">
        <i class="icon icon-cancel"></i>
        {t}cancel{/t}
      </a>

      {if $canDelete}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-danger float-right"
          data-confirm="{t}Are you sure you want to delete this domain?{/t}">
          <i class="icon icon-trash"></i>
          {t}delete{/t}
        </button>
      {/if}

    </div>
  </form>
{/block}
