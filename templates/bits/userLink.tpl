{strip}

{if $u}

  {if $u->fileExtension}
    {include "bits/image.tpl"
      obj=$u
      geometry=Config::THUMB_USER_NAVBAR
      imgClass="rounded"}
  {else}
    {include "bits/icon.tpl" i=person}
  {/if}

  <a href="{Router::userLink($u)}" class="user-link text-muted">
    {$u|escape}
  </a>

{/if}

{/strip}
