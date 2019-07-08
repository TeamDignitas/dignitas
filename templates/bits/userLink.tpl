{strip}

{if $u->imageExtension}
  {include "bits/image.tpl"
    obj=$u
    size=Config::THUMB_NAVBAR
    imgClass="rounded"}
{else}
  <i class="icon icon-user"></i>
{/if}

<a
  href="{Router::link('user/view')}/{$u->id}/{$u->nickname|escape:url}"
  class="userLink text-muted">
  {$u|escape}
</a>

{/strip}
