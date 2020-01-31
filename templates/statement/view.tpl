{extends "layout.tpl"}

{block "title"}{cap}{$statement->summary|escape}{/cap}{/block}

{block "content"}
  {include "bits/statement.tpl" editLink=true}

  {if count($answers)}
    <h4 class="mt-4">
      {t count=count($answers) 1=count($answers) plural="%1 answers"}one answer{/t}
    </h4>

    {foreach $answers as $answer}
      {include "bits/answer.tpl" highlighted=($answer->id == $answerId)}
    {/foreach}
  {/if}

  {if User::may(User::PRIV_ADD_ANSWER)}
    <h4 class="mt-3">{t}your answer{/t}</h4>

    {capture "buttonText"}{t}post your answer{/t}{/capture}
    {include "bits/answerEdit.tpl"
      answer=$newAnswer
      buttonText=$smarty.capture.buttonText}

  {/if}

  {include "bits/flagModal.tpl"}

{/block}
