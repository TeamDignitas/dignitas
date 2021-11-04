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

  <span class="ms-2"></span>

  {if $showEditLink && $fromEntity->isEditable()}
    <a
      class="btn btn-sm px-0"
      href="{Router::link('relation/edit')}/{$r->id}"
      title="{t}link-edit{/t}">
      {include "bits/icon.tpl" i=mode_edit}
    </a>
  {/if}

  {$links=$r->getLinks()}
  {if count($links)}
    <a
      class="btn btn-sm px-0"
      data-bs-toggle="collapse"
      href="#collapse-links-{$r->id}"
      title="{t}relation-links{/t}">
      {include "bits/icon.tpl" i=info}
    </a>

    <div class="small text-muted ms-2 collapse" id="collapse-links-{$r->id}">
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
