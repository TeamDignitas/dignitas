{* Recursively displays a region tree (or forest). *}
{if $regions}
  <ul>
    {foreach $regions as $r}
      <li>
        {include "bits/region.tpl"}
        {include "bits/regionTree.tpl" regions=$r->children}
      </li>
    {/foreach}
  </ul>
{/if}
