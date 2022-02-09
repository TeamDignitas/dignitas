{extends "layout.tpl"}

{block "title"}{cap}{t}title-sign-up{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <div class="card col-12 col-md-8 col-lg-6 m-auto">
      <h3 class="card-title m-4">
        {cap}{t}title-sign-up{/t}{/cap}
      </h3>

      <div class="card-body">
        <form method="post" class="needs-validation" novalidate>

          {include "bs/iconField.tpl"
            icon='person'
            ifErrors=$errors.nickname|default:null
            name='nickname'
            placeholder="{t}label-nickname{/t}"
            value=$nickname}

          {include "bs/iconField.tpl"
            icon='email'
            ifErrors=$errors.email|default:null
            name='email'
            placeholder="{t}label-email{/t}"
            type='email'
            value=$email}

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

          {include 'bs/checkbox.tpl'
            checked=$remember
            divClass='mb-1'
            label="{t}label-remember-me{/t}"
            name='remember'}

          {capture 'label'}
            {t 1=Router::link('help/index')}label-register-manual{/t}
          {/capture}
          {include 'bs/checkbox.tpl'
            cbErrors=$errors.manual|default:null
            checked=$manual
            divClass='mb-1'
            label=$smarty.capture.label
            name='manual'
            required=true}

          <div class="mt-2 mx-2 row">
            <button class="btn btn-primary col-12 col-md-6" type="submit" name="submitButton">
              {t}link-sign-up{/t}
            </button>

            <a class="btn btn-link col-12 col-md-6 text-center" href="{Router::link('auth/login')}">
              {t}link-has-account-already{/t}
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
{/block}
