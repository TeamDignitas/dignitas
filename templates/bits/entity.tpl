{$statusInfo=$entity->getStatusInfo()}
{$flagBox=$flagBox|default:true}

<div class="clearfix">
  {include "bits/image.tpl"
    obj=$entity
    geometry=Config::THUMB_ENTITY_LARGE
    imgClass="pic float-right"}

  <h3>
    {$entity->name|escape}
    {if $statusInfo}
      [{$statusInfo['status']}]
    {/if}
  </h3>
  <h4>{$entity->getTypeName()}</h4>

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
        {include "bits/reviewFlagList.tpl" obj=$entity}
      {/if}
    </div>
  {/if}

  <ul>
    {foreach $entity->getRelations() as $r}
      <li>
        {include "bits/relation.tpl" fromEntity=$entity}
      </li>
    {/foreach}
  </ul>

  {if $entity->type == Entity::TYPE_PERSON}
    <h4>{t}loyalty{/t}</h4>

    {include "bits/loyalty.tpl" data=$entity->getLoyalty()}
  {/if}
</div>

{$aliases=$entity->getAliases()}
{if count($aliases)}
  <h4>{t}also known as{/t}</h4>

  <ul class="list-unstyled">
    {foreach $aliases as $a}
      <li>{$a->name|escape}
    {/foreach}
  </ul>
{/if}

{if $entity->profile}
  <h4>{t}profile{/t}</h4>

  <div>
    {$entity->profile|md}
  </div>
{/if}

{$links=$entity->getLinks()}
{if count($links)}
  <h4>{t}external links{/t}</h4>

  <ul id="links" class="list-inline list-inline-bullet">
    {foreach $links as $l}
      <li class="list-inline-item">
        <a href="{$l->url}">{$l->getDisplayUrl()}</a>
      </li>
    {/foreach}
  </ul>
{/if}

{$members=$entity->getMembers()}
{if count($members)}
  <h4>{t}members{/t}</h4>

  <ul>
    {foreach $members as $m}
      <li>
        {include "bits/entityLink.tpl" e=$m}
      </li>
    {/foreach}
  </ul>
{/if}

<div>
  {if $entity->isEditable()}
    <a href="{Router::link('entity/edit')}/{$entity->id}" class="btn btn-light">
      <i class="icon icon-edit"></i>
      {t}edit{/t}
    </a>
  {/if}

  {if $flagBox && ($entity->isFlaggable() || $entity->isFlagged())}
    {include "bits/flagLinks.tpl" obj=$entity class="btn btn-link text-muted"}
  {/if}
</div>
