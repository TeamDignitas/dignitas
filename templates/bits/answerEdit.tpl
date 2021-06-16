{* mandatory arguments: $answer, $buttonText, $referrer *}
<form
  id="answer-edit"
  method="post"
  action="{Router::link('answer/edit')}"
  class="col-md-12 px-0">

  <input type="hidden" name="id" value="{$answer->id}">
  <input type="hidden" name="statementId" value="{$answer->statementId}">
  <input type="hidden" name="referrer" value="{$referrer}">

  <div class="mb-3 clearfix">
    <textarea
      name="contents"
      class="form-control has-unload-warning easy-mde {if isset($errors.contents)}is-invalid{/if}"
      data-statement-id="{$answer->statementId}"
      rows="10">{$answer->contents|escape}</textarea>
    {include "bs/feedback.tpl" errors=$errors.contents|default:null}
    {include "bits/markdownHelp.tpl"}
  </div>

  <div class="row mb-3">
    <label for="field-verdict" class="col-md-6 col-sm-4 col-form-label text-end">
      {t}label-verdict{/t}

      {$url=LocaleUtil::getVerdictUrl()}
      {if $url}
        <span class="ms-2">
          <a href="{$url}"
            title="{t}link-verdict-details{/t}"
            target="_blank">
            {include "bits/icon.tpl" i=help}
          </a>
        </span>
      {/if}
    </label>

    <div class="col-md-6 col-sm-8">
      <select
        id="field-verdict"
        name="verdict"
        class="form-select has-unload-warning">
        {foreach $answer->getStatement()->getVerdictChoices() as $v}
          <option
            value="{$v}"
            {if $v == $answer->verdict}selected{/if}>
            {Statement::verdictName($v)}
          </option>
        {/foreach}
      </select>
    </div>
  </div>

  {include "bs/actions.tpl"
    cancelLink=$referrer
    deleteButton=$answer->isDeletable()
    deleteButtonConfirm="{t}info-confirm-delete-answer{/t}"
    divClass='answer-buttons'
    reopenButton=$answer->isReopenable()
    reopenButtonConfirm="{t}info-confirm-reopen-answer{/t}"
    saveButtonText=$buttonText
    saveDraftButton=($answer->status == Ct::STATUS_DRAFT)}

</form>

{include "bits/answerResources.tpl"}
