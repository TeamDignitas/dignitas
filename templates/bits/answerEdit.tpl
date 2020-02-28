{* mandatory arguments: $answer, $buttonText, $referrer *}
<form method="post" action="{Router::link('answer/edit')}" class="col-md-12 px-0">
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

  <div class="form-group row">
    <label for="fieldVerdict" class="col-md-2 offset-md-4 col-form-label text-right">
      {t}label-verdict{/t}
    </label>

    <div class="col-md-6">
      <select
        id="fieldVerdict"
        name="verdict"
        class="form-control hasUnloadWarning">
        {for $v = 0 to Ct::NUM_VERDICTS - 1}
          <option
            value="{$v}"
            {if $v == $answer->verdict}selected{/if}>
            {Statement::verdictName($v)}
          </option>
        {/for}
      </select>
    </div>
  </div>

  <div class="text-right">
    <button name="saveButton" type="submit" class="btn btn-outline-primary">
      <i class="icon icon-floppy"></i>
      {$buttonText}
    </button>
    <a href="{$referrer}" class="btn btn-outline-secondary">
      <i class="icon icon-cancel"></i>
      {t}link-cancel{/t}
    </a>
  </div>
</form>
