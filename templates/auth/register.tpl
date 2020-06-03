{extends "layout.tpl"}

{block "title"}{cap}{t}title-sign-up{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <div class="card w-50 m-auto">
      <h3 class="card-title m-4">
        {cap}{t}title-sign-up{/t}{/cap}
      </h3>

      <div class="card-body">
        <form method="post">

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-prepend">
                <i class="input-group-text icon icon-user"></i>
              </span>
              <input
                class="form-control {if isset($errors.nickname)}is-invalid{/if}"
                type="text"
                name="nickname"
                value="{$nickname|escape}"
                autofocus
                placeholder="{t}label-nickname{/t}">
            </div>
            {include "bits/fieldErrors.tpl" errors=$errors.nickname|default:null}
          </div>

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-prepend">
                <i class="input-group-text icon icon-mail"></i>
              </span>
              <input
                class="form-control {if isset($errors.email)}is-invalid{/if}"
                type="email"
                name="email"
                value="{$email|escape}"
                placeholder="{t}label-email{/t}">
            </div>
            {include "bits/fieldErrors.tpl" errors=$errors.email|default:null}
          </div>

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-prepend">
                <i class="input-group-text icon icon-lock"></i>
              </span>
              <input
                class="form-control {if isset($errors.password)}is-invalid{/if}"
                type="password"
                name="password"
                value="{$password|escape}"
                placeholder="{t}label-password{/t}">
            </div>
            {include "bits/fieldErrors.tpl" errors=$errors.password|default:null}
          </div>

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-prepend">
                <i class="input-group-text icon icon-lock"></i>
              </span>
              <input
                class="form-control"
                type="password"
                name="password2"
                value="{$password2|escape}"
                placeholder="{t}label-password-again{/t}">
            </div>
          </div>

          <div class="form-check">
            <label>
              <input
                type="checkbox"
                class="form-check-input"
                name="remember"
                value="1"
                {if $remember}checked{/if}>
              {t}label-remember-me{/t}
            </label>
          </div>

          <div class="mt-2 mx-2 row">
            <button class="btn btn-primary col-sm-12 col-md-6" type="submit" name="submitButton">
              {t}link-sign-up{/t}
            </button>

            <a class="btn btn-link col-sm-12 col-md-6 text-center" href="{Router::link('auth/login')}">
              {t}link-has-account-already{/t}
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
{/block}
