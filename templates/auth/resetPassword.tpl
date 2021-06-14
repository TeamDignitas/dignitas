{extends "layout.tpl"}

{block "title"}{cap}{t}title-reset-password{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    {if $user}
      <div class="card col-sm-12 col-md-8 col-lg-6 m-auto">
        <div class="card-header">
          {cap}{t}title-reset-password{/t}{/cap}
        </div>

        <div class="card-body">
          <form method="post">

            <div class="form-group">
              <div class="input-group">
                {include "bits/icon.tpl" i=lock class="input-group-text"}
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
              <div class="input-group voffset3">
                {include "bits/icon.tpl" i=lock class="input-group-text"}
                <input
                  class="form-control"
                  type="password"
                  name="password2"
                  value="{$password2|escape}"
                  placeholder="{t}label-password-again{/t}">
              </div>
            </div>

            <button class="btn btn-primary" type="submit" name="submitButton">
              {t}link-save{/t}
            </button>
          </form>
        </div>
      </div>
    {/if}
  </div>
{/block}
