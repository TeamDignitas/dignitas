{* mandatory arguments: $r, $fromEntity *}
{$class=$class|default:''}
{$showEditLink=$showEditLink|default:true}
{assign var="sd" value=$r->startDate|ld}
{assign var="ed" value=$r->endDate|ld}

<div class="{$class} {if $r->ended()}text-muted{/if}">
  {$rt=$r->getRelationType()}
  {$rt->name|escape}

  {$to=$r->getToEntity()}
  {include "bits/entityLink.tpl" e=$to phrase=$rt->phrase}

  {$r->getDateRangeString()}

  {if $showEditLink && $fromEntity->isEditable()}
    <a
      class="small text-muted ml-2"
      href="{Router::link('relation/edit')}/{$r->id}">
      {include "bits/icon.tpl" i=mode_edit}
      {t}relation-links{/t}
    </a>
  {/if}

  {$links=$r->getLinks()}
  {if count($links)}
    <div class="small text-muted">
      {t}label-relation-links{/t}:
      <ul class="list-inline list-inline-bullet d-inline">
        {foreach $links as $l}
          <li class="list-inline-item">
            {include "bits/link.tpl"}
          </li>
        {/foreach}
      </ul>
    </div>
  {/if}
</div>
