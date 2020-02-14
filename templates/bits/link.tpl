{strip}
<a
  href="{$l->url}"
  {if $l->isNofollow()}rel="nofollow"{/if}>

  {if $l->domainId}
    {include "bits/image.tpl"
      obj=$l->getDomain()
      geometry=Config::THUMB_DOMAIN}
  {/if}

  {$l->getDisplayValue()}

</a>
{/strip}
