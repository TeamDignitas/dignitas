{extends "layout.tpl"}

{block "title"}to be determined{/block}

{block "content"}

  <h3>{t}recent statements{/t}</h3>

  {include "bits/statementList.tpl"}

  <h3>{t}entities{/t}</h3>

  {foreach $entities as $e}
    <div class="clearfix">
      {if $e->imageExtension}
        <img
          src="{$e->getThumbLink(0)}"
          class="img-thumbnail rounded float-right">
      {/if}

      {include "bits/entityLink.tpl" e=$e}
      <div>{$e->getTypeName()}</div>
    </div>
    <hr>
  {/foreach}

  <div>
    {if User::may(User::PRIV_ADD_STATEMENT)}
      <a href="{Router::link('statement/edit')}" class="btn btn-link">
        {t}add a statement{/t}
      </a>
    {/if}

    {if User::may(User::PRIV_ADD_ENTITY)}
      <a href="{Router::link('entity/edit')}" class="btn btn-link">
      {t}add an author{/t}
      </a>
    {/if}
  </div>

{/block}
