{extends "layout.tpl"}

{block "title"}{cap}{t}title-log-in{/t}{/cap}{/block}

{block "content"}
  <div class="container mt-5">
    {$allowFakeLogins=$allowFakeLogins|default:false}

    <div class="w-50 mx-auto">

      {if $allowFakeLogins}
        {include "bits/fakeLogin.tpl"}
      {/if}

      <div class="my-5">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a id="login-tab" class="nav-link active" href="#login" data-toggle="tab" role="tab" aria-controls="login" aria-selected="true">{cap}{t}title-log-in{/t}{/cap}</a>
          </li>
          <li class="nav-item">
            {if Config::ALLOW_REGISTRATION}
              <a id="signup-tab" class="nav-link capitalize-first-word" href="#signup" data-toggle="tab" role="tab" aria-controls="signup" aria-selected="false">
                {t}link-sign-up{/t}
              </a>
            {/if}
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active mt-5 mx-2" id="login" role="tabpanel" aria-labelledby="login-tab">

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

                <a class="btn btn-link" href="{Router::link('auth/lostPassword')}">
                  {t}link-forgot-password{/t}
                </a>
              </div>
            </form>
          </div>

          <div class="tab-pane fade mt-5 mx-2" id="signup" role="tabpanel" aria-labelledby="signup-tab">
            {if Config::ALLOW_REGISTRATION}
              <a class="btn btn-outline-secondary" href="{Router::link('auth/register')}">
                <i class="icon icon-user-plus"></i>
                {t}link-sign-up{/t}
              </a>
            {/if}
          </div>
        </div>
      </div>

    </div>
  </div>
{/block}
