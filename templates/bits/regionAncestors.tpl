{$root=$root|default:false}
{$link=$link|default:false}

<ul class="breadcrumb py-0">
  {if $root}
    <li class="breadcrumb-item">
      <a href="{Router::link('region/list')}">
        {cap}{t}link-regions{/t}{/cap}
      </a>
    </li>
  {/if}
  {foreach $region->getAncestors() as $i => $r name=loop}
    {$l=$link || !$smarty.foreach.loop.last}
    <li class="breadcrumb-item">
      {include "bits/region.tpl" link=$l}
    </li>
  {/foreach}
</ul>
