{extends "layout.tpl"}

{block "title"}{cap}{t}title-log-in{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    {$allowFakeLogins=$allowFakeLogins|default:false}

    <div class="col-12 col-md-8 col-lg-6 mx-auto">

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

            {include "bs/iconField.tpl"
              autofocus=!$email
              icon='email'
              ifErrors=$errors.email|default:null
              name='email'
              placeholder="{t}label-email{/t}"
              type='email'
              value=$email}

            {include "bs/iconField.tpl"
              autofocus=!empty($email)
              icon='lock'
              ifErrors=$errors.password|default:null
              mb=2
              name='password'
              placeholder="{t}label-password{/t}"
              type='password'}

            <a class="small ms-5 mb-3 d-block" href="{Router::link('auth/lostPassword')}">
              {t}link-forgot-password{/t}
            </a>

            {include 'bs/checkbox.tpl'
              checked=$remember
              label="{t}label-remember-me{/t}"
              name='remember'}

            <div class="mt-3 mx-2 row">
              <button class="btn btn-primary col-12 col-md-6" type="submit" name="submitButton">
                {t}link-log-in{/t}
              </button>

              <div class="col-12 col-md-6 text-center">
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
