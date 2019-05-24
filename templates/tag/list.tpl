{extends "layout.tpl"}

{block "title"}{cap}{t}tags{/t}{/cap}{/block}

{block "content"}

  <h3>{cap}{t}tags{/t}{/cap}</h3>

  {if User::may(User::PRIV_ADD_TAG)}
    <a class="btn btn-light" href="{Router::link('tag/edit')}">
      <i class="icon icon-plus"></i>
      {t}add a tag{/t}
    </a>
  {/if}

  <div id="tagTree" class="voffset3">
    {include "bits/tagTree.tpl" link=User::may(User::PRIV_EDIT_TAG)}
  </div>

{/block}
