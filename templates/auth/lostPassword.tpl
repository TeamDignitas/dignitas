{extends "layout.tpl"}

{block "title"}{cap}{t}title-lost-password{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <div class="col-sm-12 col-md-8 col-lg-6 mx-auto">

      <div class="card">
        <div class="card-header">
          {cap}{t}title-lost-password{/t}{/cap}
        </div>

        <div class="card-body">
          <p>
            {t}info-password-recovery-process{/t}
          </p>

          <form method="post">

            <div class="form-group">
              <div class="input-group">
                {include "bits/icon.tpl" i=email class="input-group-text"}
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

            <button
              class="btn btn-primary col-sm-12 col-md-6"
              type="submit"
              name="submitButton">

              {include "bits/icon.tpl" i=send}
              {t}link-send{/t}

            </button>

          </form>
        </div>
      </div>
    </div>
  </div>
{/block}
