{extends "layout.tpl"}

{block "title"}{$entity->name|escape}{/block}

{block "content"}
  <div class="clearfix">
    {include "bits/image.tpl"
      obj=$entity
      size=Config::THUMB_ENTITY_LARGE
      imgClass="pic float-right"}

    <h3>{$entity->name|escape}</h3>
    <h4>{$entity->getTypeName()}</h4>

    <ul>
      {foreach $relations as $r}
        <li>
          {include "bits/relation.tpl"}
        </li>
      {/foreach}
    </ul>

    {if $entity->type == Entity::TYPE_PERSON}
      <h4>{t}loyalty{/t}</h4>

      {include "bits/loyalty.tpl" data=$entity->getLoyalty()}
    {/if}

    <div>
      {if User::may(User::PRIV_EDIT_ENTITY)}
        <a href="{Router::link('entity/edit')}/{$entity->id}" class="btn btn-light">
          <i class="icon icon-edit"></i>
          {t}edit{/t}
        </a>
      {/if}
    </div>
  </div>

  {if count($aliases)}
    <h4>{cap}{t}also known as{/t}{/cap}</h4>

    <ul class="list-unstyled">
      {foreach $aliases as $a}
        <li>{$a->name|escape}
      {/foreach}
    </ul>
  {/if}

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

  {if count($statements)}
    <h4>{cap}{t}statements{/t}{/cap}</h4>
    {include "bits/statementList.tpl" entityImages=false}
  {/if}

{/block}
