{extends "layout.tpl"}

{block "title"}{t}title-send-invite{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4 capitalize-first-word">{t}title-send-invite{/t}</h1>

    <form method="post">

      <div class="form-group">
        <label for="field-email">{t}label-email{/t}</label>
        <div class="input-group">
          <span class="input-group-prepend">
            {include "bits/icon.tpl" i=email class="input-group-text"}
          </span>
          <input
            class="form-control {if isset($errors.email)}is-invalid{/if}"
            type="text"
            name="email"
            value="{$i->email|escape}"
            autofocus
            placeholder="{t}label-email{/t}">
        </div>
        {include "bits/fieldErrors.tpl" errors=$errors.email|default:null}
      </div>

      <div class="mt-4 text-end">
        <a href="{Router::link('invite/list')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mb-2 me-2">
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
          {include "bits/icon.tpl" i=send}
          {t}link-send{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
