{* mandatory arguments: $answer, $buttonText, $referrer *}
<form method="post" action="{Router::link('answer/edit')}">
  <input type="hidden" name="id" value="{$answer->id}">
  <input type="hidden" name="statementId" value="{$answer->statementId}">
  <input type="hidden" name="referrer" value="{$referrer}">

  <div class="form-group">
    <textarea
      id="fieldContents"
      name="contents"
      class="form-control hasUnloadWarning {if isset($errors.contents)}is-invalid{/if}"
      rows="10">{$answer->contents|escape}</textarea>
    {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
    {include "bits/markdownHelp.tpl"}
  </div>

  <div>
    <button name="saveButton" type="submit" class="btn btn-primary">
      <i class="icon icon-floppy"></i>
      {$buttonText}
    </button>
    <a href="{$referrer}" class="btn btn-link">
      <i class="icon icon-cancel"></i>
      {t}cancel{/t}
    </a>
  </div>
</form>
