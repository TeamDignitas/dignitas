{extends "layout.tpl"}

{block "title"}{cap}{t}lost password{/t}{/cap}{/block}

{block "content"}
  <p>
    {t 1=$email}
    We sent an email to <b>%1</b>. Please click the link inside to reset
    your password.
    {/t}
  </p>
{/block}
