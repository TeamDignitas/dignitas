{$statusInfo=$entity->getStatusInfo()}
{$flagBox=$flagBox|default:true}

<div class="row">
  <div class="col-md-3 col-sm-12 mt-2 text-center">
    {include "bits/image.tpl"
        obj=$entity
        geometry=Config::THUMB_ENTITY_LARGE
        imgClass="pic rounded-circle img-fluid no-outline"}

    <div class="tags mt-2 text-center">
      {foreach $entity->getTags() as $t}
        {include "bits/tag.tpl"}
      {/foreach}
    </div>
  </div>

  <div class="col-md-9 col-sm-12 mt-2">
    <h2 class="font-weight-bold mb-0 center-mobile">
      {$entity->name|escape}
      {if $statusInfo}
        [{$statusInfo['status']}]
      {/if}
    </h2>
    <div class="font-italic text-muted pl-2 center-mobile">
      {$aliases=$entity->getAliases()}
      {if count($aliases)}
        <span class="text-capitalize">{t}title-alias{/t}:
          <ul class="d-inline list-inline">
            {foreach $aliases as $a}
              <li class="list-inline-item">{$a->name|escape}
            {/foreach}
          </ul>
        </span>
      {/if}
    </div>

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

    <h6 class="font-weight-bold text-capitalize mt-5">{t}title-relations{/t}</h6>
    <ul class="relations">
      {foreach $entity->getRelations() as $r}
        <li>
          {include "bits/relation.tpl" fromEntity=$entity}
        </li>
      {/foreach}
    </ul>

    {if $entity->type == Entity::TYPE_PERSON}
      <h6 class="font-weight-bold text-capitalize">{t}title-loyalty{/t}</h6>
      {include "bits/loyalty.tpl" data=$entity->getLoyalty()}
    {/if}

    {if $entity->profile}
      <h6 class="font-weight-bold text-capitalize mt-4">{t}title-profile{/t}</h6>
      <div>
        {$entity->profile|md}
      </div>
    {/if}

    <div>
      {$links=$entity->getLinks()}
      {if count($links)}
        <h6 class="font-weight-bold capitalize-first-word">{t}title-entity-links{/t}</h6>

        <ul id="links" class="list-inline list-inline-bullet">
          {foreach $links as $l}
            <li class="list-inline-item">
              {include "bits/link.tpl"}
            </li>
          {/foreach}
        </ul>
      {/if}
    </div>

    <hr class="w-100 title-divider mt-0 mb-2">

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

    <div class="entity-actions mb-2 text-right">
      {include "bits/editButton.tpl" obj=$entity}

      {if $flagBox && ($entity->isFlaggable() || $entity->isFlagged())}
        {include "bits/flagLinks.tpl" obj=$entity class="btn btn-sm btn-outline-secondary mt-1"}
      {/if}

      {if $entity->hasRevisions()}
        <a href="{Router::link('entity/history')}/{$entity->id}" class="btn btn-sm btn-outline-secondary mt-1">
          {t}link-show-revisions{/t}
        </a>
      {/if}
    </div>

  </div>
</div>
