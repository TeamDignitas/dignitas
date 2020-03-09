{strip}
<div>

  <div class="loyalty-widget" data-toggle="popover">
    {foreach $data as $rec}
      {$percent=($rec.value*100)|nf:0}
      {$color=$rec.entity->getColor()}
      <div style="width: {$percent}%; background-color: {$color};"></div>
    {/foreach}
  </div>

  <div class="loyalty-popover" hidden>
    {foreach $data as $rec}
      <div>
        {$percent=($rec.value*100)|nf:0}
        {$rec.entity->name|escape}: {$percent}%
      </div>
    {/foreach}
  </div>
</div>
{/strip}
