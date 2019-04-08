{extends "layout.tpl"}

{block "title"}to be determined{/block}

{block "content"}

  <a href="{Router::link('statement/edit')}" class="btn-link">
    {t}add a statement{/t}
  </a>

{/block}
