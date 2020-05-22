{extends "layout.tpl"}

{block "title"}{cap}{t}title-log-in{/t}{/cap}{/block}

{block "content"}
  <div class="container mt-5">
    {$allowFakeLogins=$allowFakeLogins|default:false}

    <div class="w-50 mx-auto">

      {if $allowFakeLogins}
        {include "bits/fakeLogin.tpl"}
      {/if}

      <div class="card my-5">
        <h3 class="card-title m-4">
          {cap}{t}title-log-in{/t}{/cap}
        </h3>

        <div class="card-body">
          <form method="post">
            <input type="hidden" name="referrer" value="{$referrer|escape}">

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-prepend">
                  <i class="input-group-text icon icon-mail"></i>
                </span>
                <input
                  class="form-control {if isset($errors.email)}is-invalid{/if}"
                  type="text"
                  name="email"
                  value="{$email|escape}"
                  autofocus
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
                  placeholder="{t}label-password{/t}">
              </div>
              <a class="btn btn-sm btn-link ml-5 mt-1" href="{Router::link('auth/lostPassword')}">
                {t}link-forgot-password{/t}
              </a>
              {include "bits/fieldErrors.tpl" errors=$errors.password|default:null}
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

            <div class="mt-2">
              <button class="btn btn-primary w-50" type="submit" name="submitButton">
                {t}link-log-in{/t}
              </button>

              <div class="float-right">
                {if Config::ALLOW_REGISTRATION}
                  <a class="btn btn-link" href="{Router::link('auth/register')}">
                    {t}link-sign-up{/t}
                  </a>
                {/if}
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
{/block}
