{extends "layout.tpl"}

{block "title"}to be determined{/block}

{block "content"}

  <ul>
    {foreach $statements as $s}
      <li>
        <b>{$s->entityId|escape}</b>
        &nbsp;
        {$s->contents|escape}
      </li>
    {/foreach}
  </ul>

  <a href="{Router::link('statement/edit')}" class="btn-link">
    {t}add a statement{/t}
  </a>

{/block}
