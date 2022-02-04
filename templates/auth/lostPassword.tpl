{extends "layout.tpl"}

{block "title"}{cap}{t}title-lost-password{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <div class="col-12 col-md-8 col-lg-6 mx-auto">

      <div class="card">
        <div class="card-header">
          {cap}{t}title-lost-password{/t}{/cap}
        </div>

        <div class="card-body">
          <p>
            {t}info-password-recovery-process{/t}
          </p>

          <form method="post">

            {include "bs/iconField.tpl"
              autofocus=true
              icon='email'
              ifErrors=$errors.email|default:null
              name='email'
              placeholder="{t}label-email{/t}"
              type='email'
              value=$email}

            <button
              class="btn btn-primary col-12 col-md-6"
              type="submit"
              name="submitButton">

              {t}link-send{/t}

            </button>

          </form>
        </div>
      </div>
    </div>
  </div>
{/block}
