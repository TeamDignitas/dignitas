{extends "layout.tpl"}

{block "title"}{t}label-edit-user{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}label-edit-user{/t}</h1>

    <form method="post" enctype="multipart/form-data">

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label class="col-sm-12 col-lg-2 mt-2 px-0">{t}label-nickname{/t}</label>

          <div class="col-sm-12 col-lg-10 px-0">
            <div class="input-group">
              <span class="input-group-prepend">
                {include "bits/icon.tpl" i=person class="input-group-text"}
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
        </div>

        <div class="form-group row">
          <label class="col-sm-12 col-lg-2 mt-2 px-0">{t}label-email{/t}</label>

          <div class="col-sm-12 col-lg-10 px-0">
            <div class="input-group">
              <span class="input-group-prepend">
                {include "bits/icon.tpl" i=email class="input-group-text"}
              </span>
              <input
                class="form-control {if isset($errors.email)}is-invalid{/if}"
                type="email"
                name="email"
                value="{$user->email|escape}">
            </div>
            {include "bits/fieldErrors.tpl" errors=$errors.email|default:null}
          </div>
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label class="col-sm-12 col-lg-2 mt-2 px-0">{t}label-about-me{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
            <textarea
              name="aboutMe"
              class="form-control has-unload-warning easy-mde"
              rows="5">{$user->aboutMe|escape}</textarea>
            {include "bits/markdownHelp.tpl"}
          </div>
        </div>

        <div class="form-group row">
          <label for="field-image" class="col-sm-12 col-lg-2 mt-2 px-0">{t}label-image{/t}</label>

          <div class="col-sm-12 col-lg-10 px-0">
            <div class="custom-file">
              <input
                id="field-image"
                name="image"
                type="file"
                data-bs-toggle="tooltip"
                title="{t}tooltip-upload-user-image{/t}"
                class="custom-file-input {if isset($errors.image)}is-invalid{/if}">
              <label class="custom-file-label" for="field-image">
                {t}info-upload-image{/t}
              </label>
            </div>
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
          </div>
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5 ml-3">
        <legend class="row capitalize-first-word">
          {t}title-change-password{/t}
        </legend>
        <div class="form-group row">
          <label class="col-sm-12 col-lg-2 mt-2 px-0">{t}label-password{/t}</label>

          <div class="col-sm-12 col-lg-10 px-0">
            <div class="input-group">
              <span class="input-group-prepend">
                {include "bits/icon.tpl" i=lock class="input-group-text"}
              </span>
              <input
                class="form-control {if isset($errors.password)}is-invalid{/if}"
                type="password"
                name="password"
                value="{$password|default:''}">
            </div>
            {include "bits/fieldErrors.tpl" errors=$errors.password|default:null}
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-12 col-lg-2 mt-2 px-0">{t}label-password-again{/t}</label>
          <div class="input-group col-sm-12 col-lg-10 px-0">
            <span class="input-group-prepend">
              {include "bits/icon.tpl" i=lock class="input-group-text"}
            </span>
            <input
              class="form-control"
              type="password"
              name="password2"
              value="{$password2|default:''}">
          </div>
        </div>
      </fieldset>

      <div class="mt-4 text-end">
        <a href="{Router::userLink($user)}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
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
