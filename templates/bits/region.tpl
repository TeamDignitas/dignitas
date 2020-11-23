{$link=$link|default:false}

{if $link}
  <a href="{Router::link('region/view')}/{$r->id}">{$r->name}</a>
{else}
  {$r->name}
{/if}
