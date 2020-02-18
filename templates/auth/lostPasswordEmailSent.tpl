{extends "layout.tpl"}

{block "title"}{cap}{t}title-lost-password{/t}{/cap}{/block}

{block "content"}
  <p>
    {t 1=$email}info-password-recovery-email-sent{/t}
  </p>
{/block}
