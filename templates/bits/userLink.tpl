{strip}

{if $u->imageExtension}
  {include "bits/image.tpl"
    obj=$u
    geometry=Config::THUMB_USER_NAVBAR
    imgClass="rounded"}
{else}
  <i class="icon icon-user"></i>
{/if}

<a href="{Router::userLink($u)}" class="userLink text-muted">
  {$u|escape}
</a>

{/strip}
