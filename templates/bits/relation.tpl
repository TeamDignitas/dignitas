{assign var="sd" value=$r->startDate|ld|default:'?'}
{assign var="ed" value=$r->endDate|ld|default:'?'}
{assign var="ended" value=($r->endDate && $r->endDate < Util::today())}

<div {if $ended}class="text-muted"{/if}>
  {$r->getTypeName()}

  {$to=$r->getToEntity()}
  <a href="{Router::link('entity/view')}/{$to->id}">
    {$to->name}
  </a>

  {if $r->startDate && $r->endDate}
    {t 1=$sd 2=$ed}from %1 to %2{/t}
  {else if $r->startDate}
    {t 1=$sd}since %1{/t}
  {else if $r->endDate}
    {t 1=$ed}until %1{/t}
  {/if}
</div>
