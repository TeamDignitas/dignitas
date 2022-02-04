{extends "layout.tpl"}

{block "title"}{t}title-tag{/t}: {$tag->value}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{t}title-tag{/t}
      {include "bits/tagAncestors.tpl"}
    </h1>

    {if User::may(User::PRIV_EDIT_TAG)}
      <p>
        <a href="{Router::link('tag/edit')}/{$tag->id}" class="btn btn-sm btn-primary">
          {t}link-edit{/t}
        </a>
        <a href="{Router::link('tag/list')}" class="btn btn-sm btn-link">
          {t}link-tag-list{/t}
        </a>
      </p>
    {/if}

    {if count($tag->children)}
      <h4 class="mt-5 capitalize-first-word">
        {t}title-descendants{/t}
      </h4>

      <div id="tag-tree" class="mt-4">
        {include "bits/tagTree.tpl" tags=$tag->children link=User::may(User::PRIV_EDIT_TAG)}
      </div>
    {/if}

    {if count($statements)}
      <h4 class="mt-5 capitalize-first-word">
        {t}title-statements{/t}
      </h4>

      <div id="statement-wrapper">
        {include "bits/statementList.tpl"}
      </div>
      {include "bits/paginationWrapper.tpl"
        n=$statementPages
        url="{Config::URL_PREFIX}ajax/get-tag-statements/{$tag->id}"
        target="#statement-wrapper"}
    {/if}
  </div>
{/block}
