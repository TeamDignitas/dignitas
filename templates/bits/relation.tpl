{assign var="sd" value=$r->startDate|ld|default:'?'}
{assign var="ed" value=$r->endDate|ld|default:'?'}
{assign var="ended" value=($r->endDate && $r->endDate < Time::today())}

<div {if $ended}class="text-muted"{/if}>
  {$r->getTypeName()}

  {$to=$r->getToEntity()}
  {include "bits/entityLink.tpl" e=$to},

  {if $r->startDate && $r->endDate}
    {$sd} – {$ed}
  {else if $r->startDate}
    {$sd}
  {else if $r->endDate}
    {$ed}
  {/if}
</div>
