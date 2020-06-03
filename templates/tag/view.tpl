{extends "layout.tpl"}

{block "title"}{t}title-tag{/t}: {$tag->value}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{t}title-tag{/t}
    {include "bits/tagAncestors.tpl"}
    </h1>

    {if User::may(User::PRIV_EDIT_TAG)}
      <p>
        <a href="{Router::link('tag/edit')}/{$tag->id}" class="btn btn-sm btn-outline-primary">
          <i class="icon icon-edit"></i>
          {t}link-edit{/t}
        </a>
      </p>
    {/if}

    {if count($statements)}
      <h4 class="mt-5 capitalize-first-word">
        {if $statementCount > count($statements)}
          {t 1=count($statements) 2=$statementCount}title-statement-count-with-limit{/t}
        {else}
          {t 1=count($statements)}title-statement-count{/t}
        {/if}
      </h4>

      {include "bits/statementList.tpl"}
    {/if}
  </div>
{/block}
