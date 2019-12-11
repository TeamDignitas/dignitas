{* This template should only be reachable for existing anwers. *}
{extends "layout.tpl"}

{block "title"}{cap}{t}edit answer{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}edit answer{/t}{/cap}</h3>

  {if !$answer->isEditable()}
    <div class="alert alert-warning">
      {t}You do not have enough reputation to make changes directly. You can
      suggest changes which will be placed in the review queue.{/t}
    </div>
  {/if}

  {capture "buttonText"}{t}save{/t}{/capture}
  {include "bits/answerEdit.tpl" buttonText=$smarty.capture.buttonText}
{/block}
