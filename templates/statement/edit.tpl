{extends "layout.tpl"}

{block "title"}{cap}{t}add a statement{/t}{/cap}{/block}

{block "content"}
  <h3>{t}add a statement{/t}</h3>

  <form method="post">
    <input type="hidden" name="id" value="{$statement->id}">
    <div class="form-group">
      <label for="fieldEntityId">{t}author{/t}</label>
      <input
        name="entityId"
        value="{$statement->entityId}"
        id="fieldEntityId"
        class="form-control {if isset($errors.entityId)}is-invalid{/if}">
      {include "bits/fieldErrors.tpl" errors=$errors.entityId|default:null}
    </div>

    <div class="form-group">
      <label for="fieldContents">{t}contents{/t}</label>
      <textarea
        name="contents"
        id="fieldContents"
        class="form-control {if isset($errors.contents)}is-invalid{/if}"
        rows="10">{$statement->contents}</textarea>
      {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
    </div>

    <button name="saveButton" type="submit" class="btn btn-primary">
      <i class="icon icon-floppy"></i>
      {t}save{/t}
    </button>
  </form>
{/block}
