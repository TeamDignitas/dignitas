<p>
  {foreach $tag->getAncestors() as $i => $t}
    {if $i}
      <i class="icon icon-right-open"></i>
    {/if}
    {include "bits/tag.tpl" link=true}
  {/foreach}
</p>
