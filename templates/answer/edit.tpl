{* This template should only be reachable for existing anwers. *}
{extends "layout.tpl"}

{block "title"}{cap}{t}edit answer{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}edit answer{/t}{/cap}</h3>

  {capture "buttonText"}{t}save{/t}{/capture}
  {include "bits/answerEdit.tpl" buttonText=$smarty.capture.buttonText}
{/block}
