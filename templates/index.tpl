{extends "layout.tpl"}

{block "title"}to be determined{/block}

{block "content"}

  <h3>{t}recent statements{/t}</h3>

  {include "bits/statementList.tpl"}

  <h3>{t}entities{/t}</h3>

  {foreach $entities as $e}
    <div>
      {if $e->imageExtension}
        <img
          src="{$e->getThumbLink(0)}"
          class="img-thumbnail rounded float-right">
        {/if}
      <b>{$e->name|escape}</b>
      <div>{$e->getTypeName()}</div>

      <div>
        <a href="{Router::link('entity/edit')}/{$e->id}">{t}edit{/t}</a>
      </div>

      <hr>
  {/foreach}

  <div>
    <a href="{Router::link('statement/edit')}" class="btn btn-link">
      {t}add a statement{/t}
    </a>

    <a href="{Router::link('entity/edit')}" class="btn btn-link">
      {t}add an author{/t}
    </a>
  </div>

{/block}
