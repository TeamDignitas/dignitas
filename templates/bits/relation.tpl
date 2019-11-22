{assign var="sd" value=$r->startDate|ld}
{assign var="ed" value=$r->endDate|ld}
{assign var="ended" value=($r->endDate && $r->endDate < Time::today())}

<div {if $ended}class="text-muted"{/if}>
  {$r->getTypeName()}

  {$to=$r->getToEntity()}
  {include "bits/entityLink.tpl" e=$to}

  {if $sd && $ed}
    ({$sd} – {$ed})
  {elseif $sd}
    ({t}since{/t} {$sd})
  {elseif $ed}
    ({t}until {$ed}{/t})
  {/if}
</div>
