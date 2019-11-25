{* mandatory arguments: $r, $fromEntity *}
{$class=$class|default:''}
{$showSourceLink=$showSourceLink|default:true}
{assign var="sd" value=$r->startDate|ld}
{assign var="ed" value=$r->endDate|ld}

<div class="{$class} {if $r->ended()}text-muted{/if}">
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

  {if $showSourceLink && $fromEntity->isEditable()}
    <a
      class="small text-muted ml-2"
      href="{Router::link('relation/edit')}/{$r->id}">
      <i class="icon icon-edit"></i>
      {t}sources{/t}
    </a>
  {/if}

  {$sources=$r->getSources()}
  {if count($sources)}
    <div class="small text-muted">
      {t}sources{/t}:
      <ul class="list-inline list-inline-bullet d-inline">
        {foreach $r->getSources() as $s}
          <li class="list-inline-item">
            <a href="{$s->url}">{$s->getDisplayUrl()}</a>
          </li>
        {/foreach}
      </ul>
    </div>
  {/if}
</div>
