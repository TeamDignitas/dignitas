{extends "layout.tpl"}

{block "title"}{cap}{t}title-log-in{/t}{/cap}{/block}

{block "content"}
  <div class="container mt-4">
    {$allowFakeLogins=$allowFakeLogins|default:false}

    <div class="w-50 mx-auto">

      {if $allowFakeLogins}
        {include "bits/fakeLogin.tpl"}
      {/if}

      <div class="card my-3">
        <div class="card-header">
          {cap}{t}title-log-in{/t}{/cap}
        </div>

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

            <div>
              <button class="btn btn-primary" type="submit" name="submitButton">
                {t}link-log-in{/t}
              </button>

              <div class="float-right">
                <a class="btn btn-outline-secondary" href="{Router::link('auth/lostPassword')}">
                  <i class="icon icon-help"></i>
                  {t}link-forgot-password{/t}
                </a>

                {if Config::ALLOW_REGISTRATION}
                  <a class="btn btn-outline-secondary" href="{Router::link('auth/register')}">
                    <i class="icon icon-user-plus"></i>
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
