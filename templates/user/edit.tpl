{extends "layout.tpl"}

{block "title"}{t}edit your profile{/t}{/block}

{block "content"}
  <h3>{t}edit your profile{/t}</h3>

  <form method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label>{t}nickname{/t}</label>

      <div class="input-group">
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

    <div class="form-group">
      <label>{t}email{/t}</label>

      <div class="input-group">
        <span class="input-group-prepend">
          <i class="input-group-text icon icon-mail"></i>
        </span>
        <input
          class="form-control {if isset($errors.email)}is-invalid{/if}"
          type="email"
          name="email"
          value="{$user->email}">
      </div>
      {include "bits/fieldErrors.tpl" errors=$errors.email|default:null}
    </div>

    <div class="form-group row">
      <div class="col">
        <label for="fieldImage">{t}image{/t}</label>

        <div class="custom-file">
          <input
            name="image"
            type="file"
            class="form-control-file {if isset($errors.image)}is-invalid{/if}"
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

      {include "bits/image.tpl"
        obj=$user
        size=Config::THUMB_USER_PROFILE
        spanClass="col-3"
        imgClass="img-thumbnail rounded float-right"}
    </div>

    <div class="form-group">
      <label>{t}about me{/t}</label>
      <textarea
        id="fieldAboutMe"
        name="aboutMe"
        class="form-control hasUnloadWarning"
        rows="5">{$user->aboutMe}</textarea>
      {include "bits/markdownHelp.tpl"}
    </div>

    <h4>{t}preview{/t}</h4>

    <div id="markdownPreview">
      {$user->aboutMe|md}
    </div>

    <hr>

    <div class="form-group">
      <label>{t}password (only if you wish to change it){/t}</label>

      <div class="input-group">
        <span class="input-group-prepend">
          <i class="input-group-text icon icon-lock"></i>
        </span>
        <input
          class="form-control {if isset($errors.password)}is-invalid{/if}"
          type="password"
          name="password"
          value="{$password|default:''}">
      </div>
      {include "bits/fieldErrors.tpl" errors=$errors.password|default:null}
    </div>

    <div class="form-group">
      <label>{t}password (again){/t}</label>
      <div class="input-group">
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

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}save{/t}
      </button>

      <a href="" class="btn btn-light">
        <i class="icon icon-cancel"></i>
        {t}cancel{/t}
      </a>
    </div>
  </form>
{/block}
