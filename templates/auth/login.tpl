{extends "layout.tpl"}

{block "title"}{cap}{t}title-log-in{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    {$allowFakeLogins=$allowFakeLogins|default:false}

    <div class="col-sm-12 col-md-8 col-lg-6 mx-auto">

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
                {include "bits/icon.tpl" i=email class="input-group-text"}
                <input
                  class="form-control {if isset($errors.email)}is-invalid{/if}"
                  type="text"
                  name="email"
                  value="{$email|escape}"
                  {if !$email}autofocus{/if}
                  placeholder="{t}label-email{/t}">
              </div>
              {include "bits/fieldErrors.tpl" errors=$errors.email|default:null}
            </div>

            <div class="form-group">
              <div class="input-group">
                {include "bits/icon.tpl" i=lock class="input-group-text"}
                <input
                  class="form-control {if isset($errors.password)}is-invalid{/if}"
                  type="password"
                  name="password"
                  {if $email}autofocus{/if}
                  placeholder="{t}label-password{/t}">
              </div>
              <a class="btn btn-sm btn-link ms-5 mt-1" href="{Router::link('auth/lostPassword')}">
                {t}link-forgot-password{/t}
              </a>
              {include "bits/fieldErrors.tpl" errors=$errors.password|default:null}
            </div>

            {include 'bs/checkbox.tpl'
              checked=$remember
              label="{t}label-remember-me{/t}"
              name='remember'}

            <div class="mt-2 mx-2 row">
              <button class="btn btn-primary col-sm-12 col-md-6" type="submit" name="submitButton">
                {t}link-log-in{/t}
              </button>

              <div class="col-sm-12 col-md-6 text-center">
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
