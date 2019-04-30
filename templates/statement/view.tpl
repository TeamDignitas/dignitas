{extends "layout.tpl"}

{block "title"}{cap}{$statement->summary|escape}{/cap}{/block}

{block "content"}
  <div class="clearfix">
    {if $entity->imageExtension}
      <img
        src="{$entity->getThumbLink(1)}"
        class="img-thumbnail rounded float-right ml-5">
    {/if}

    <h3>{$statement->summary|escape}</h3>

    <p>
      --
      {include "bits/entityLink.tpl" e=$entity},
      {$statement->dateMade|ld}
    </p>

    <h4>{t}context{/t}</h4>

    {$statement->context|md}

    <h4>{t}goal{/t}</h4>

    {$statement->goal|escape}
  </div>

  <div class="mt-3 clearfix">
    {if $statement->isEditable()}
      <a href="{Router::link('statement/edit')}/{$statement->id}" class="btn btn-light">
        <i class="icon icon-edit"></i>
        {t}edit{/t}
      </a>
    {/if}

    <small class="btn text-muted float-right">
      {t}added by{/t} <b>{$statement->getUser()|escape}</b>
      {$statement->createDate|moment}
    </small>
  </div>

{/block}
