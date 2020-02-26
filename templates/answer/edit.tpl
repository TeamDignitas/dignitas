{* This template should only be reachable for existing anwers. *}
{extends "layout.tpl"}

{block "title"}{cap}{t}title-edit-answer{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}title-edit-answer{/t}{/cap}</h3>

  {if !$answer->isEditable()}
    <div class="alert alert-warning">
      {$answer->getEditMessage()}
    </div>
  {/if}

  {capture "buttonText"}{t}link-save{/t}{/capture}
  {include "bits/answerEdit.tpl" buttonText=$smarty.capture.buttonText}
{/block}
