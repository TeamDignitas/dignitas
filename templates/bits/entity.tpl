<div class="clearfix">
  {include "bits/image.tpl"
    obj=$entity
    geometry=Config::THUMB_ENTITY_LARGE
    imgClass="pic float-right"}

  <h3>{$entity->name|escape}</h3>
  <h4>{$entity->getTypeName()}</h4>

  {if $entity->status == Ct::STATUS_DELETED}
    <div class="alert alert-secondary">
      {$entity->getDeletedMessage()}

      {if $entity->reason == Ct::REASON_BY_USER}
        {include "bits/userLink.tpl" u=$entity->getStatusUser()}
      {else if $entity->reason != Ct::REASON_BY_OWNER}
        <hr>
        {include "bits/reviewFlagList.tpl" obj=$entity}
      {/if}
    </div>
  {/if}

  <ul>
    {foreach $entity->getRelations() as $r}
      <li>
        {include "bits/relation.tpl"}
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
  <h4>{cap}{t}also known as{/t}{/cap}</h4>

  <ul class="list-unstyled">
    {foreach $aliases as $a}
      <li>{$a->name|escape}
    {/foreach}
  </ul>
{/if}

{$members=$entity->getMembers()}
{if count($members)}
  <h4>{cap}{t}members{/t}{/cap}</h4>

  <ul>
    {foreach $members as $m}
      <li>
        {include "bits/entityLink.tpl" e=$m}
      </li>
    {/foreach}
  </ul>
{/if}

<div>
  {if User::may(User::PRIV_EDIT_ENTITY)}
    <a href="{Router::link('entity/edit')}/{$entity->id}" class="btn btn-light">
      <i class="icon icon-edit"></i>
      {t}edit{/t}
    </a>
  {/if}

  {if ($entity->isFlaggable() || $entity->isFlagged())}
    {include "bits/flagLinks.tpl" obj=$entity class="btn btn-link text-muted"}
  {/if}
</div>
