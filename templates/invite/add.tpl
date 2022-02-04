{extends "layout.tpl"}

{block "title"}{t}title-send-invite{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4 capitalize-first-word">{t}title-send-invite{/t}</h1>

    <form method="post">

      {include "bs/iconField.tpl"
        autofocus=true
        icon='email'
        ifErrors=$errors.email|default:null
        name='email'
        placeholder="{t}label-email{/t}"
        type='email'
        value=$i->email}

      <div class="mt-4 text-end">
        <a href="{Router::link('invite/list')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mb-2 me-2">
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
          {t}link-send{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
