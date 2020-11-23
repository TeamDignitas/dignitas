{$link=$link|default:false}

<ul class="breadcrumb mb-0 pb-0">
  {foreach $region->getAncestors() as $i => $r name=loop}
    {$l=$link || !$smarty.foreach.loop.last}
    <li class="breadcrumb-item">
      {include "bits/region.tpl" link=$l}
    </li>
  {/foreach}
</ul>
