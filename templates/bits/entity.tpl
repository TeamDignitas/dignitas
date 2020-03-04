{$statusInfo=$entity->getStatusInfo()}
{$flagBox=$flagBox|default:true}

<div class="row entity-profile">
  <div class="col-md-3">
    {include "bits/image.tpl"
        obj=$entity
        geometry=Config::THUMB_ENTITY_LARGE
        imgClass="pic float-right"}
  </div>

  <div class="col-md-9">
    <h3>
      {$entity->name|escape}
      {if $statusInfo}
        [{$statusInfo['status']}]
      {/if}
      <span class="font-italic text-muted">({$entity->getTypeName()})</span>
    </h3>

    {if $statusInfo}
      <div class="alert {$statusInfo['cssClass']} overflow-hidden">
        {$statusInfo['details']}
        {if $statusInfo['dup']}
          {include "bits/entityLink.tpl"
            e=$statusInfo['dup']
            class="alert-link"}
        {/if}
        {if $entity->reason == Ct::REASON_BY_USER}
          {include "bits/userLink.tpl" u=$entity->getStatusUser()}
        {elseif $entity->reason != Ct::REASON_BY_OWNER}
          <hr>
          {include "bits/reviewFlagList.tpl" flags=$entity->getReviewFlags()}
        {/if}
      </div>
    {/if}

    <h4>Relatii</h4>
    <ul class="relations">
      {foreach $entity->getRelations() as $r}
        <li>
          {include "bits/relation.tpl" fromEntity=$entity}
        </li>
      {/foreach}
    </ul>

    {if $entity->type == Entity::TYPE_PERSON}
      <h4>{t}title-loyalty{/t}</h4>
      {include "bits/loyalty.tpl" data=$entity->getLoyalty()}
    {/if}

    {$aliases=$entity->getAliases()}
    {if count($aliases)}
      <h4>{t}title-alias{/t}</h4>

      <ul class="list-unstyled">
        {foreach $aliases as $a}
          <li>{$a->name|escape}
        {/foreach}
      </ul>
    {/if}

    {if $entity->profile}
      <h4 class="text-capitalize">{t}title-profile{/t}</h4>
      <div>
        {$entity->profile|md}
      </div>
    {/if}

    <div class="entity-links">
      {$links=$entity->getLinks()}
      {if count($links)}
        <h4>{t}title-entity-links{/t}</h4>

        <ul id="links" class="list-inline list-inline-bullet">
          {foreach $links as $l}
            <li class="list-inline-item">
              {include "bits/link.tpl"}
            </li>
          {/foreach}
        </ul>
      {/if}
    </div>

    <div class="tags">
      {foreach $entity->getTags() as $t}
        {include "bits/tag.tpl"}
      {/foreach}
    </div>

    <div class="title-members">
      {$members=$entity->getMembers()}
      {if count($members)}
        <h4>{t}title-members{/t}</h4>

        <ul>
          {foreach $members as $m}
            <li>
              {include "bits/entityLink.tpl" e=$m}
            </li>
          {/foreach}
        </ul>
      {/if}
    </div>

    <div class="entity-actions">
      {include "bits/editButton.tpl" obj=$entity}

      {if $flagBox && ($entity->isFlaggable() || $entity->isFlagged())}
        {include "bits/flagLinks.tpl" obj=$entity class="btn btn-link text-muted"}
      {/if}

      {if $entity->hasRevisions()}
        <a href="{Router::link('entity/history')}/{$entity->id}" class="btn btn-sm btn-link">
          {t}link-show-revisions{/t}
        </a>
      {/if}
    </div>

  </div>
</div>
