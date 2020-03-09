{strip}

{if $u->fileExtension}
  {include "bits/image.tpl"
    obj=$u
    geometry=Config::THUMB_USER_NAVBAR
    imgClass="rounded"}
{else}
  <i class="icon icon-user"></i>
{/if}

<a href="{Router::userLink($u)}" class="user-link text-muted">
  {$u|escape}
</a>

{/strip}
