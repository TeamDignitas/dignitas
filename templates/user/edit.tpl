{extends "layout.tpl"}

{block "title"}{t}label-edit-user{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}label-edit-user{/t}</h1>

    <form method="post" enctype="multipart/form-data">

      <fieldset class="related-fields mb-5 ms-3">
        {hf label="{t}label-nickname{/t}"}
          {include "bs/iconField.tpl"
            icon='person'
            ifErrors=$errors.nickname|default:null
            name='nickname'
            value=$user->nickname}
        {/hf}

        {hf label="{t}label-email{/t}"}
          {include "bs/iconField.tpl"
            icon='email'
            ifErrors=$errors.email|default:null
            name='email'
            type='email'
            value=$user->email}
        {/hf}
      </fieldset>

      <fieldset class="related-fields mb-5 ms-3">
        {hf label="{t}label-about-me{/t}"}
          <textarea
            name="aboutMe"
            class="form-control has-unload-warning easy-mde"
            rows="5">{$user->aboutMe|escape}</textarea>
          {include "bits/markdownHelp.tpl"}
        {/hf}

        {hf inputId='field-image' label="{t}label-image{/t}"}
          <input
            class="form-control {if isset($errors.image)}is-invalid{/if}"
            data-bs-toggle="tooltip"
            id="field-image"
            name="image"
            title="{t}tooltip-upload-user-image{/t}"
            type="file">
          {include "bits/fieldErrors.tpl" errors=$errors.image|default:null}

          {include 'bs/checkbox.tpl'
            divClass='mt-1'
            label="{t}label-delete-image{/t}"
            name='deleteImage'}

          {include "bits/image.tpl"
            obj=$user
            geometry=Config::THUMB_USER_PROFILE
            spanClass="col-3"
            imgClass="pic float-right"}
        {/hf}
      </fieldset>

      <fieldset class="related-fields mb-5 ms-3">
        <legend class="row capitalize-first-word">
          {t}title-change-password{/t}
        </legend>

        {hf label="{t}label-password{/t}"}
          {include "bs/iconField.tpl"
            icon='lock'
            ifErrors=$errors.password|default:null
            name='password'
            type='password'
            value=$password}
        {/hf}

        {hf label="{t}label-password-again{/t}"}
          {include "bs/iconField.tpl"
            icon='lock'
            name='password2'
            type='password'
            value=$password2}
        {/hf}
      </fieldset>

      <div class="mt-4 text-end">
        <a href="{Router::userLink($user)}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2">
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
