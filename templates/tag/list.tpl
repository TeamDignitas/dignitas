{extends "layout.tpl"}

{block "title"}{cap}{t}title-tags{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-tags{/t}{/cap}</h1>

    {if User::may(User::PRIV_ADD_TAG)}
      <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('tag/edit')}">
        {t}link-add-tag{/t}
      </a>
    {/if}

    <a
      class="btn btn-sm btn-outline-secondary col-12 col-md-3"
      data-other-text="{t}link-collapse-all{/t}"
      href="#"
      id="link-expand-all">
      {t}link-expand-all{/t}
    </a>

    <div id="tag-tree" class="mt-4">
      {include "bits/tagTree.tpl" tags=$root->children link=User::may(User::PRIV_EDIT_TAG)}
    </div>
  </div>
{/block}
