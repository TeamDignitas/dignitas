{$ellipsisMenu=$ellipsisMenu|default:true}
{$showAddStatementButton=$showAddStatementButton|default:true}
{$showTrustLevel=$showTrustLevel|default:true}
{$statusInfo=$entity->getStatusInfo()}

<div class="row">
  <div class="col-12 col-md-3 mt-2 text-center">
    {include "bits/image.tpl"
      obj=$entity
      geometry=Config::THUMB_ENTITY_LARGE
      imgClass="rounded-circle img-fluid"
      link=true}

    <div class="tags mt-2 text-center">
      {foreach $entity->getTags() as $t}
        {include "bits/tag.tpl"}
      {/foreach}
    </div>

    {if $showTrustLevel}
      <div class="mt-4 d-flex justify-content-center">
        {include "bits/trustLevel.tpl" val=$trustLevel}
      </div>
    {/if}
  </div>

  <div class="col-12 col-md-9 mt-2">
    <h1 class="fw-bold mb-0 center-mobile">
      {$entity->name|esc}
      {if $statusInfo}
        [{$statusInfo['status']}]
      {/if}
    </h1>
    <div class="font-italic text-muted ps-2 center-mobile">
      {$aliases=$entity->getAliases()}
      {if count($aliases)}
        <span class="capitalize-first-word d-inline-block">{t}title-alias{/t}:</span>
        <ul class="d-inline list-inline list-inline-bullet">
          {foreach $aliases as $a}
            <li class="list-inline-item">{$a->name|esc}
          {/foreach}
        </ul>
      {/if}
    </div>

    {if $statusInfo}
      {notice icon=warning}
        {$statusInfo['details']}
        {if $statusInfo['dup']}
          {include "bits/entityLink.tpl" e=$statusInfo['dup']}
        {/if}
        {if $entity->reason == Ct::REASON_BY_USER}
          {include "bits/userLink.tpl" u=$entity->getStatusUser()}
        {elseif $entity->reason != Ct::REASON_BY_OWNER}
          <hr>
          {include "bits/reviewFlagList.tpl" review=$entity->getRemovalReview()}
        {/if}
      {/notice}
    {/if}

    {$relations=$entity->getRelations()}
    {if count($relations)}
      <h6 class="fw-bold capitalize-first-word mt-4">{t}title-relations{/t}</h6>
      <ul class="relations">
        {foreach $relations as $r}
          <li>
            {include "bits/relation.tpl" fromEntity=$entity}
          </li>
        {/foreach}
      </ul>
    {/if}

    {if $entity->regionId}
      <h6 class="fw-bold capitalize-first-word mt-4">{t}title-region{/t}</h6>
      {include "bits/regionAncestors.tpl" region=$entity->getRegion()}
    {/if}

    {include "bits/loyalty.tpl" data=$entity->getLoyalties()}

    {if $entity->profile}
      <h6 class="fw-bold capitalize-first-word mt-4">{t}title-profile{/t}</h6>
      <div class="archivable">
        {$entity->profile|md}
      </div>
    {/if}

    {$links=$entity->getLinks()}
    {if count($links)}
      <h6 class="fw-bold capitalize-first-word mt-4">{t}title-entity-links{/t}</h6>

      <ul id="links" class="list-inline list-inline-bullet">
        {foreach $links as $l}
          <li class="list-inline-item">
            {include "bits/link.tpl"}
          </li>
        {/foreach}
      </ul>
    {/if}

    <div class="title-members">
      {$members=$entity->getMembers()}
      {if count($members)}
        <h6 class="fw-bold capitalize-first-word mt-4">{t}title-members{/t}</h6>

        <ul>
          {foreach $members as $m}
            <li>
              {include "bits/entityLink.tpl" e=$m}
            </li>
          {/foreach}
        </ul>
      {/if}
    </div>

    <hr class="w-100 title-divider mt-0 mb-2">

    <div class="entity-actions mb-2 text-end">
      {if $showAddStatementButton && $entity->acceptsNewStatements()}
        <a
          href="{Router::link('statement/edit')}?entityId={$entity->id}"
          class="btn btn-primary mt-1">
          {t}link-add-statement{/t}
        </a>
      {/if}

      {if $ellipsisMenu}
        <button
          class="btn mt-1"
          type="button"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false">
          {include "bits/icon.tpl" i=more_vert}
        </button>
        <div class="dropdown-menu" aria-labelledby="entity-menu">

          {include "bits/editButton.tpl" obj=$entity}
          {include "bits/subscribeLinks.tpl" obj=$entity}
          {include "bits/flagLinks.tpl" obj=$entity}
          {include "bits/historyButton.tpl" obj=$entity class="dropdown-item"}
          {include "bits/viewMarkdownButton.tpl" obj=$entity}
        </div>
      {/if}
    </div>

  </div>
</div>
