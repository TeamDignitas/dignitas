{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  <h3>{t}title-recent-statements{/t}</h3>

  {include "bits/statementList.tpl"}

  <h3>{t}title-entities{/t}</h3>

  {foreach $entities as $e}
    <div class="clearfix">
      {include "bits/image.tpl"
        obj=$e
        geometry=Config::THUMB_ENTITY_SMALL
        imgClass="pic float-right"}

      {include "bits/entityLink.tpl" e=$e showStatus=true}
      <div>{$e->getTypeName()}</div>
    </div>
    <hr>
  {/foreach}

  <div>
    {if User::may(User::PRIV_ADD_STATEMENT)}
      <a href="{Router::link('statement/edit')}" class="btn btn-link">
        {t}link-add-statement{/t}
      </a>
    {/if}

    {if User::may(User::PRIV_ADD_ENTITY)}
      <a href="{Router::link('entity/edit')}" class="btn btn-link">
        {t}link-add-entity{/t}
      </a>
    {/if}
  </div>

{/block}
