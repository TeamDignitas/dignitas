{extends "layout.tpl"}

{block "title"}{cap}{t}title-tags{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-tags{/t}{/cap}</h1>

    {if User::may(User::PRIV_ADD_TAG)}
      <a class="btn btn-sm btn-primary" href="{Router::link('tag/edit')}">
        <i class="icon icon-plus"></i>
        {t}link-add-tag{/t}
      </a>
    {/if}

    <div id="tag-tree" class="voffset3 mt-4">
      {include "bits/tagTree.tpl" link=User::may(User::PRIV_EDIT_TAG)}
    </div>
  </div>
{/block}
