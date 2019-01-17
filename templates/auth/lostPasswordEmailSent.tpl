{extends "layout.tpl"}

{block "title"}{cap}{t}lost password{/t}{/cap}{/block}

{block "content"}
  <p>
    We sent an email to <b>{$email}</b>. Please click the link inside to reset
    your password.
  </p>
{/block}
