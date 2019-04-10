{extends "layout.tpl"}

{block "title"}to be determined{/block}

{block "content"}

  <h3>{t}statements{/t}</h3>

  {foreach $statements as $s}
    <div>
      <b>{$s->entityId|escape}</b>
      <div>{$s->contents|md}</div>

      <div>
        <a href="{Router::link('statement/edit')}?id={$s->id}">{t}edit{/t}</a>
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
