{$statusInfo=$entity->getStatusInfo()}

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
    <h1 class="font-weight-bold mb-0 center-mobile">
      {$entity->name|escape}
      {if $statusInfo}
        [{$statusInfo['status']}]
      {/if}
    </h1>
    <div class="font-italic text-muted pl-2 center-mobile">
      {$aliases=$entity->getAliases()}
      {if count($aliases)}
        <span class="capitalize-first-word d-inline-block">{t}title-alias{/t}:</span>
        <ul class="d-inline list-inline list-inline-bullet">
          {foreach $aliases as $a}
            <li class="list-inline-item">{$a->name|escape}
          {/foreach}
        </ul>
      {/if}
    </div>

    {if $statusInfo}
      <div class="alert {$statusInfo['cssClass']} small overflow-hidden">
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
          {include "bits/reviewFlagList.tpl" review=$entity->getRemovalReview()}
        {/if}
      </div>
    {/if}

    <h6 class="font-weight-bold capitalize-first-word mt-5">{t}title-relations{/t}</h6>
    <ul class="relations">
      {foreach $entity->getRelations() as $r}
        <li>
          {include "bits/relation.tpl" fromEntity=$entity}
        </li>
      {/foreach}
    </ul>

    {if $entity->regionId}
      <h6 class="font-weight-bold capitalize-first-word mt-4">{t}title-region{/t}</h6>
      {include "bits/regionAncestors.tpl" region=$entity->getRegion() link=true}
    {/if}

    {include "bits/loyalty.tpl" data=$entity->getLoyalties()}

    {if $entity->profile}
      <h6 class="font-weight-bold capitalize-first-word mt-4">{t}title-profile{/t}</h6>
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

    <div class="title-members">
      {$members=$entity->getMembers()}
      {if count($members)}
        <h6 class="font-weight-bold capitalize-first-word">{t}title-members{/t}</h6>

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

    <div class="entity-actions mb-2 text-right">
      {if $entity->acceptsNewStatements()}
        <a
          href="{Router::link('statement/edit')}?entityId={$entity->id}"
          class="btn btn-primary mt-1">
          {t}link-add-statement{/t}
        </a>
      {/if}

      <button
        class="btn mt-1"
        type="button"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
        <i class="icon icon-ellipsis-vert"></i>
      </button>
      <div class="dropdown-menu" aria-labelledby="entity-menu">

        {include "bits/editButton.tpl" obj=$entity class="dropdown-item"}
        {include "bits/subscribeLinks.tpl" obj=$entity class="dropdown-item"}
        {include "bits/flagLinks.tpl" obj=$entity class="dropdown-item"}
        {include "bits/historyButton.tpl" obj=$entity class="dropdown-item"}
      </div>
    </div>

  </div>
</div>
