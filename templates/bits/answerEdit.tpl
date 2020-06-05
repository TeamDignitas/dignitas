{* mandatory arguments: $answer, $buttonText, $referrer *}
<form method="post" action="{Router::link('answer/edit')}" class="col-md-12 px-0">
  <input type="hidden" name="id" value="{$answer->id}">
  <input type="hidden" name="statementId" value="{$answer->statementId}">
  <input type="hidden" name="referrer" value="{$referrer}">

  <div class="form-group">
    <textarea
      name="contents"
      class="form-control has-unload-warning easy-mde {if isset($errors.contents)}is-invalid{/if}"
      rows="10">{$answer->contents|escape}</textarea>
    {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
    {include "bits/markdownHelp.tpl"}
  </div>

  <div class="form-group row">
    <label for="field-verdict" class="col-md-6 col-sm-4 col-form-label text-right">
      {t}label-verdict{/t}

      {if Config::VERDICT_URL}
        <span class="ml-2">
          <a href="{Config::VERDICT_URL}"
            title="{t}link-verdict-details{/t}"
            target="_blank">
            <i class="icon icon-help"></i>
          </a>
        </span>
      {/if}
    </label>

    <div class="col-md-6 col-sm-8">
      <select
        id="field-verdict"
        name="verdict"
        class="form-control has-unload-warning">
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

  <div class="text-right answer-buttons">
    <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
      <i class="icon icon-floppy"></i>
      {$buttonText}
    </button>

    <a href="{$referrer}" class="btn btn-sm btn-outline-secondary">
      <i class="icon icon-cancel"></i>
      {t}link-cancel{/t}
    </a>

    {if $answer->isDeletable()}
      <button
        name="deleteButton"
        type="submit"
        class="btn btn-sm btn-outline-danger"
        data-confirm="{t}info-confirm-delete-answer{/t}">
        <i class="icon icon-trash"></i>
        {t}link-delete{/t}
      </button>
    {/if}

    {if $answer->isReopenable()}
      <button
        name="reopenButton"
        type="submit"
        class="btn btn-sm btn-outline-secondary"
        data-confirm="{t}info-confirm-reopen-answer{/t}">
        {t}link-reopen{/t}
      </button>
    {/if}
  </div>
</form>
