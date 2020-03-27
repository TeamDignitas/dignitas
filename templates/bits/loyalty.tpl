{if $entity->type == Entity::TYPE_PERSON && $entity->hasLoyalties()}
  {$data=$entity->getLoyalties()}
  <h6 class="font-weight-bold text-capitalize">{t}title-loyalty{/t}</h6>

  {* use floor, not round, to ensure the sum doesn't exceed 100% *}
  {strip}
  <div>
    <div class="loyalty-widget" data-toggle="popover">
      {foreach $data as $e}
        {$percent=(floor($e->value*10000)/100)|number_format:2}
        {$color=$e->getColor()}
        <div style="width: {$percent}%; background-color: {$color};"></div>
      {/foreach}
    </div>

    <div class="loyalty-popover" hidden>
      {foreach $data as $e}
        <div>
          {$percent=($e->value*100)|nf:0}
          {$e->name|escape}: {$percent}%
        </div>
      {/foreach}
    </div>
  </div>
  {/strip}

{/if}
