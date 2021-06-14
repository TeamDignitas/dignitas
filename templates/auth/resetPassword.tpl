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

            {include "bs/iconField.tpl"
              icon='lock'
              ifErrors=$errors.password|default:null
              name='password'
              placeholder="{t}label-password{/t}"
              type='password'
              value=$password}

            {include "bs/iconField.tpl"
              icon='lock'
              name='password2'
              placeholder="{t}label-password-again{/t}"
              type='password'
              value=$password2}

            <button class="btn btn-primary" type="submit" name="submitButton">
              {t}link-save{/t}
            </button>
          </form>
        </div>
      </div>
    {/if}
  </div>
{/block}
