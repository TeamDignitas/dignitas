{extends "layout.tpl"}

{block "title"}{cap}{$statement->summary|escape}{/cap}{/block}

{block "content"}
  {$addComment=User::canComment($statement)}
  {include "bits/statement.tpl" editLink=true}

  {if count($answers)}
    <div class="row">
      <h6 class="mt-4 answer-label text-uppercase font-weight-bold">
        {t count=count($answers) 1=count($answers) plural="title-answers-plural"}
        title-answers-singular
        {/t}
      </h6>
      <hr class="w-100 mt-0 title-divider"/>
    </div>

    {foreach $answers as $answer}
      {include "bits/answer.tpl" highlighted=($answer->id == $answerId)}
    {/foreach}
  {/if}

  {if User::may(User::PRIV_ADD_ANSWER)}
    <h4 class="row mt-3 answer-label">{t}title-your-answer{/t}</h4>

    {capture "buttonText"}{t}link-post-answer{/t}{/capture}
    {include "bits/answerEdit.tpl"
      answer=$newAnswer
      buttonText=$smarty.capture.buttonText}

  {/if}

  {include "bits/commentForm.tpl"}

  {include "bits/flagModal.tpl"}

{/block}
