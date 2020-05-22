{* This template should only be reachable for existing anwers. *}
{extends "layout.tpl"}

{block "title"}{cap}{t}title-edit-answer{/t}{/cap}{/block}

{block "content"}
  <div class="container mt-5">
    <h1 class="mb-5">{cap}{t}title-edit-answer{/t}{/cap}</h1>

    {if !$answer->isEditable()}
      <div class="alert alert-warning">
        {$answer->getEditMessage()}
      </div>
    {/if}

    {capture "buttonText"}{t}link-save{/t}{/capture}
    {include "bits/answerEdit.tpl" buttonText=$smarty.capture.buttonText}
  </div>
{/block}
