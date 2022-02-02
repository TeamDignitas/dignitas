{* This template should only be reachable for existing anwers. *}
{extends "layout.tpl"}

{block "title"}{cap}{t}title-edit-answer{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-edit-answer{/t}{/cap}</h1>

    {if !$answer->isEditable()}
      {notice icon=info}
        {$answer->getEditMessage()}
      {/notice}
    {/if}

    {capture "buttonText"}
      {if $answer->isDraftOrNew()}
        {t}link-publish{/t}
      {else}
        {t}link-save{/t}
      {/if}
    {/capture}
    {include "bits/answerEdit.tpl" buttonText=$smarty.capture.buttonText}
  </div>
{/block}
