{extends "layout.tpl"}

{block "title"}{cap}{t}lost password{/t}{/cap}{/block}

{block "content"}
  <div class="w-50 mx-auto">

    <div class="card">
      <div class="card-header">
        {cap}{t}lost password{/t}{/cap}
      </div>

      <div class="card-body">
        <p>
          {t}Enter your email address and we will send you a link to reset your password.{/t}
        </p>

        <form method="post">

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-prepend">
                <i class="input-group-text icon icon-mail"></i>
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

          <button class="btn btn-primary" type="submit" name="submitButton">
            {t}send{/t}
          </button>

        </form>
      </div>
    </div>
  </div>
{/block}
