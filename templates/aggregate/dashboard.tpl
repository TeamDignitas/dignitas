{extends "layout.tpl"}

{block "title"}{cap}{t}dashboard{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}dashboard{/t}{/cap}</h3>

  {if User::may(User::PRIV_ADD_STATEMENT)}
    <div>
      <a href="{Router::link('statement/edit')}">
        {t}add a statement{/t}
      </a>
    </div>
  {/if}

  {if User::may(User::PRIV_ADD_ENTITY)}
    <div>
      <a href="{Router::link('entity/edit')}">
        {t}add an author{/t}
      </a>
    </div>
  {/if}

  <div>
    <a href="{Router::link('tag/list')}">
      {t}tags{/t}
    </a>
  </div>
{/block}
