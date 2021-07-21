{$root=$root|default:false}

<ul class="breadcrumb py-0">
  {if $root && User::isModerator()}
    <li class="breadcrumb-item">
      <a href="{Router::link('region/list')}">
        {cap}{t}link-regions{/t}{/cap}
      </a>
    </li>
  {/if}
  {foreach $region->getAncestors() as $i => $r}
    <li class="breadcrumb-item">
      {include "bits/region.tpl"}
    </li>
  {/foreach}
</ul>
