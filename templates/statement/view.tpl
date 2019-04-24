{extends "layout.tpl"}

{block "title"}{cap}{$statement->contents|md}{/cap}{/block}

{block "content"}
  <h3>{$statement->contents|md}</h3>

  -- <b>{$entity->name|escape}</b>, {$statement->dateMade|ld}

  <div class="text-muted">
    {t}added by{/t} <b>{$statement->getUser()|escape}</b>
    {$statement->createDate|moment}
  </div>
{/block}
