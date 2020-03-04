{$statusInfo=$entity->getStatusInfo()}
{$flagBox=$flagBox|default:true}

<div class="row entity-profile">
  <div class="col-md-3 mt-2">
    {include "bits/image.tpl"
        obj=$entity
        geometry=Config::THUMB_ENTITY_LARGE
        imgClass="pic float-right rounded-circle"}
  </div>

  <div class="col-md-9 mt-2">
    <h3 class="font-weight-bold">
      {$entity->name|escape}
      {if $statusInfo}
        [{$statusInfo['status']}]
      {/if}
    </h3>
    <span class="font-italic text-muted">{$entity->getTypeName()}</span>

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

    {$aliases=$entity->getAliases()}
    {if count($aliases)}
      <h6 class="font-weight-bold text-uppercase mt-4">{t}title-alias{/t}</h6>

      <ul class="list-unstyled">
        {foreach $aliases as $a}
          <li>{$a->name|escape}
        {/foreach}
      </ul>
    {/if}

    <h6 class="font-weight-bold text-uppercase">Relatii</h6>
    <ul class="relations">
      {foreach $entity->getRelations() as $r}
        <li>
          {include "bits/relation.tpl" fromEntity=$entity}
        </li>
      {/foreach}
    </ul>

    {if $entity->type == Entity::TYPE_PERSON}
      <h6 class="font-weight-bold text-uppercase">{t}title-loyalty{/t}</h6>
      {include "bits/loyalty.tpl" data=$entity->getLoyalty()}
    {/if}

    {if $entity->profile}
      <h6 class="font-weight-bold text-uppercase mt-4">{t}title-profile{/t}</h6>
      <div>
        {$entity->profile|md}
      </div>
    {/if}

    <div class="entity-links">
      {$links=$entity->getLinks()}
      {if count($links)}
        <h6 class="font-weight-bold text-uppercase">{t}title-entity-links{/t}</h6>

        <ul id="links" class="list-inline list-inline-bullet">
          {foreach $links as $l}
            <li class="list-inline-item">
              {include "bits/link.tpl"}
            </li>
          {/foreach}
        </ul>
      {/if}
    </div>

    <div class="tags mb-3">
      {foreach $entity->getTags() as $t}
        {include "bits/tag.tpl"}
      {/foreach}
    </div>

    <div class="title-members">
      {$members=$entity->getMembers()}
      {if count($members)}
        <h6 class="font-weight-bold text-uppercase">{t}title-members{/t}</h6>

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
