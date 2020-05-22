{extends "layout.tpl"}

{block "title"}{cap}{t}title-lost-password{/t}{/cap}{/block}

{block "content"}
  <div class="container mt-5">
    <p>
      {t 1=$email}info-password-recovery-email-sent{/t}
    </p>
  </div>
{/block}
