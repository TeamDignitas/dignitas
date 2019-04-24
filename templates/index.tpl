{extends "layout.tpl"}

{block "title"}to be determined{/block}

{block "content"}

  <h3>{t}statements{/t}</h3>

  {foreach $statements as $s}
    <div class="statement clearfix">
      {$entity=$s->getEntity()}
      {if $entity->imageExtension}
        <img
          src="{$entity->getThumbLink(1)}"
          class="img-thumbnail rounded float-right ml-5">
      {/if}

      <div>
        <div class="mb-n2">
          <a href="{Router::link('statement/view')}/{$s->id}">
            {$s->contents|md}
          </a>
        </div>

        <div class="text-right">
          -- <b>{$entity->name|escape}</b>,
          {$s->dateMade|ld}
        </div>

        <div class="text-right text-muted">
          {t}added by{/t} <b>{$s->getUser()|escape}</b>
          {$s->createDate|moment}
        </div>
      </div>
    </div>
  {/foreach}

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
