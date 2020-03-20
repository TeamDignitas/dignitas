{extends "layout.tpl"}

{block "title"}{t}title-send-invite{/t}{/block}

{block "content"}
  <h3>{t}title-send-invite{/t}</h3>

  <form method="post">

    <div class="form-group">
      <label for="field-email">{t}label-email{/t}</label>
      <div class="input-group">
        <span class="input-group-prepend">
          <i class="input-group-text icon icon-mail"></i>
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

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-paper-plane"></i>
        {t}link-send{/t}
      </button>

      <a href="{Router::link('invite/list')}" class="btn btn-light">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>
    </div>
  </form>
{/block}
