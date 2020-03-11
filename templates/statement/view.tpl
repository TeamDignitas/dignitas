{extends "layout.tpl"}

{block "title"}{cap}{$statement->summary|escape}{/cap}{/block}

{block "content"}
  {include "bits/statement.tpl"
    editLink=true
    addComment=User::canComment($statement)}

  {if count($answers)}
    <div class="row">
      <h6 class="col-md-8 mt-4 pb-2 pl-0 answer-label text-uppercase font-weight-bold">
        {t count=count($answers) 1=count($answers) plural="title-answers-plural"}
        title-answers-singular
        {/t}
      </h6>
    </div>

    {foreach $answers as $answer}
      {include "bits/answer.tpl"
        highlighted=($answer->id == $answerId)
        addComment=User::canComment($answer)}
    {/foreach}
  {/if}

  {if $statement->isAnswerable()}
    <div class="row mt-5">
      <h6 class="col-md-12 answer-label text-uppercase font-weight-bold pl-0 pb-2">
        {t}title-your-answer{/t}
      </h6>

      {capture "buttonText"}{t}link-post-answer{/t}{/capture}
      {include "bits/answerEdit.tpl"
        answer=$newAnswer
        buttonText=$smarty.capture.buttonText}
    </div>
  {/if}

  {include "bits/commentForm.tpl"}

  {include "bits/flagModal.tpl"}

{/block}
