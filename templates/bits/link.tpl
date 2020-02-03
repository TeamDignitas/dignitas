{strip}
<a
  href="{$l->url}"
  {if $l->isNofollow()}rel="nofollow"{/if}>

  {$l->getDisplayUrl()}

</a>
{/strip}
