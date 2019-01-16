{extends "layout.tpl"}

{block "title"}{cap}{t}log in{/t}{/cap}{/block}

{block "search"}{/block}

{block "content"}
  {$allowFakeLogins=$allowFakeLogins|default:false}

  <div class="w-50 mx-auto">

    {if $allowFakeLogins}
      {include "bits/fakeLogin.tpl"}
    {/if}

    <div class="card my-3">
      <div class="card-header">
        {cap}{t}log in{/t}{/cap}
      </div>

      <div class="card-body">
        <form method="post">

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-prepend">
                <i class="input-group-text icon icon-user"></i>
              </span>
              <input
                class="form-control {if isset($errors.email)}is-invalid{/if}"
                type="text"
                name="email"
                value="{$email}"
                autofocus
                placeholder="{t}email{/t}">
            </div>
            {include "bits/fieldErrors.tpl" errors=$errors.email|default:null}
          </div>

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-prepend">
                <i class="input-group-text icon icon-user"></i>
              </span>
              <input
                class="form-control {if isset($errors.password)}is-invalid{/if}"
                type="password"
                name="password"
                placeholder="{t}password{/t}">
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
              {t}remember me for one year{/t}
            </label>
          </div>

          <div>
            <button class="btn btn-primary" type="submit" name="submitButton">
              {t}log in{/t}
            </button>

            <div class="float-right">
              <a class="btn btn-link" href="parola-uitata">
                {t}I forgot my password{/t}
              </a>

              <a class="btn btn-link" href="register">{t}sign up{/t}</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

{/block}
