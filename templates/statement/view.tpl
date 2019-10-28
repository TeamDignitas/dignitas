{extends "layout.tpl"}

{block "title"}{cap}{$statement->summary|escape}{/cap}{/block}

{block "content"}
  {include "bits/statement.tpl" editLink=true}

  {if count($answers)}
    <h4 class="mt-3">
      {t count=count($answers) 1=count($answers) plural="%1 answers"}one answer{/t}
    </h4>

    {foreach $answers as $answer}
      {include "bits/answer.tpl" highlighted=($answer->id == $answerId)}
    {/foreach}
  {/if}

  {if User::may(User::PRIV_ADD_ANSWER)}
    <h4 class="mt-3">{t}your answer{/t}</h4>

    <form method="post">
      <input type="hidden" name="statementId" value="{$statement->id}">

      <div class="form-group">
        <textarea
          id="fieldContents"
          name="contents"
          class="form-control hasUnloadWarning {if isset($errors.contents)}is-invalid{/if}"
          rows="10"></textarea>
        {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
        {include "bits/markdownHelp.tpl"}
      </div>

      <div>
        <button name="postAnswerButton" type="submit" class="btn btn-primary">
          <i class="icon icon-floppy"></i>
          {t}post your answer{/t}
        </button>
      </div>
    </form>

  {/if}

  {include "bits/flagModal.tpl"}

{/block}
