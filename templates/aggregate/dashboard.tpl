{extends "layout.tpl"}

{block "title"}{cap}{t}dashboard{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}dashboard{/t}{/cap}</h3>

  <div>
    <a href="{Router::link('statement/edit')}">
      {t}add a statement{/t}
    </a>
  </div>

  <div>
    <a href="{Router::link('entity/edit')}">
      {t}add an author{/t}
    </a>
  </div>
{/block}
