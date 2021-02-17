{if $entity->getEntityType()->loyaltySource && $entity->hasLoyalties()}
  {$data=$entity->getLoyalties()}
  <h6 class="font-weight-bold capitalize-first-word">{t}title-loyalty{/t}</h6>

  <div class="d-flex">
    {* use floor, not round, to ensure the sum doesn't exceed 100% *}
    {strip}
    <div class="loyalty-widget" data-toggle="popover">
      {foreach $data as $e}
        {$percent=(floor($e->value*10000)/100)|number_format:2}
        {$color=$e->getColor()}
        <div style="width: {$percent}%; background-color: {$color};"></div>
      {/foreach}
    </div>
    {/strip}

    {if Config::LOYALTY_URL}
      <div class="ml-2">
        <a href="{Config::LOYALTY_URL}" title="{t}link-loyalty-details{/t}">
          {include "bits/icon.tpl" i=help}
        </a>
      </div>
    {/if}

    <div class="loyalty-popover" hidden>
      {foreach $data as $e}
        <div>
          {$percent=($e->value*100)|nf:0}
          {$e->name|escape}: {$percent}%
        </div>
      {/foreach}
    </div>
  </div>

{/if}
