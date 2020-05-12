{extends "layout.tpl"}

{block "title"}{t}label-edit-user{/t}{/block}

{block "content"}
  <div class="container mt-4">
    <h1 class="mb-5">{t}label-edit-user{/t}</h1>

    <form method="post" enctype="multipart/form-data">

      <fieldset class="related-fields mb-5">
        <div class="form-group row py-1 pr-1">
          <label class="col-2 mt-2">{t}label-nickname{/t}</label>

          <div class="input-group col-10">
            <span class="input-group-prepend">
              <i class="input-group-text icon icon-user"></i>
            </span>
            <input
              class="form-control {if isset($errors.nickname)}is-invalid{/if}"
              type="text"
              name="nickname"
              value="{$user->nickname}"
              autofocus>
          </div>
          {include "bits/fieldErrors.tpl" errors=$errors.nickname|default:null}
        </div>

        <div class="form-group row py-1 pr-1 mb-0">
          <label class="col-2 mt-2">{t}label-email{/t}</label>

          <div class="input-group col-10">
            <span class="input-group-prepend">
              <i class="input-group-text icon icon-mail"></i>
            </span>
            <input
              class="form-control {if isset($errors.email)}is-invalid{/if}"
              type="email"
              name="email"
              value="{$user->email|escape}">
            {include "bits/fieldErrors.tpl" errors=$errors.email|default:null}
          </div>
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5">
        <div class="form-group row py-1 pr-1">
          <label class="col-2 mt-2">{t}label-about-me{/t}</label>
          <div class="col-10">
            <textarea
              name="aboutMe"
              class="form-control has-unload-warning easy-mde"
              rows="5">{$user->aboutMe|escape}</textarea>
            {include "bits/markdownHelp.tpl"}
          </div>
        </div>

        <div class="form-group row py-1 pr-1 mb-0">
          <label for="field-image" class="col-2 mt-2">{t}label-image{/t}</label>

          <div class="col-10">
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

            {include "bits/image.tpl"
              obj=$user
              geometry=Config::THUMB_USER_PROFILE
              spanClass="col-3"
              imgClass="pic float-right"}
          </div>
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5">
        <div class="form-group row py-1 pr-1">
          <label class="col-2 mt-2">{t}label-change-password{/t}</label>

          <div class="input-group col-10">
            <span class="input-group-prepend">
              <i class="input-group-text icon icon-lock"></i>
            </span>
            <input
              class="form-control {if isset($errors.password)}is-invalid{/if}"
              type="password"
              name="password"
              value="{$password|default:''}">
            {include "bits/fieldErrors.tpl" errors=$errors.password|default:null}
          </div>
        </div>

        <div class="form-group row py-1 pr-1 mb-0">
          <label class="col-2 mt-2">{t}label-password-again{/t}</label>
          <div class="input-group col-10">
            <span class="input-group-prepend">
              <i class="input-group-text icon icon-lock"></i>
            </span>
            <input
              class="form-control"
              type="password"
              name="password2"
              value="{$password2|default:''}">
          </div>
        </div>
      </fieldset>

      <div class="mt-4">
        <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>

        <a href="{Router::userLink($user)}" class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>
      </div>
    </form>
  </div>
{/block}
