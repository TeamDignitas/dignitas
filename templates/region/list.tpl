{extends "layout.tpl"}

{block "title"}{cap}{t}title-regions{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-regions{/t}{/cap}</h1>

    {if User::may(User::PRIV_ADD_TAG)}
      <a class="btn btn-sm btn-outline-primary" href="{Router::link('region/edit')}">
        <i class="icon icon-plus"></i>
        {t}link-add-region{/t}
      </a>
    {/if}

    <div id="region-tree" class="voffset3 mt-4">
      {include "bits/regionTree.tpl" link=User::may(User::PRIV_EDIT_TAG)}
    </div>
  </div>
{/block}
