{* Recursively displays a region tree (or forest). *}
{if $regions}
  <ul>
    {foreach $regions as $r}
      <li>
        {include "bits/region.tpl" link=$link}
        {include "bits/regionTree.tpl" regions=$r->children link=$link}
      </li>
    {/foreach}
  </ul>
{/if}
