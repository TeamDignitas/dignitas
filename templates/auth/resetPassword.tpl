{extends "layout.tpl"}

{block "title"}{cap}{t}title-change-password{/t}{/cap}{/block}

{block "content"}
  {if $user}
    <div class="card w-50 m-auto">
      <div class="card-header">
        {cap}{t}title-change-password{/t}{/cap}
      </div>

      <div class="card-body">
        <form method="post">

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
          </div>

          <div class="form-group">
            <div class="input-group voffset3">
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
            {include "bits/fieldErrors.tpl" errors=$errors.password|default:null}
          </div>

          <button class="btn btn-primary" type="submit" name="submitButton">
            {t}link-save{/t}
          </button>
        </form>
      </div>
    </div>
  {/if}
{/block}
