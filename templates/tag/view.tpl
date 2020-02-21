{extends "layout.tpl"}

{block "title"}{t}title-tag{/t}: {$tag->value}{/block}

{block "content"}
  <h3>{t}title-tag{/t}: {$tag->value}</h3>

  {include "bits/tagAncestors.tpl"}

  {if User::may(User::PRIV_EDIT_TAG)}
    <p>
      <a href="{Router::link('tag/edit')}/{$tag->id}" class="btn btn-light">
        <i class="icon icon-edit"></i>
        {t}link-edit{/t}
      </a>
    </p>
  {/if}

  {if count($statements)}
    <h3>
      {if $statementCount > count($statements)}
        {t 1=count($statements) 2=$statementCount}title-statement-count-with-limit{/t}
      {else}
        {t 1=count($statements)}title-statement-count{/t}
      {/if}
    </h3>

    {include "bits/statementList.tpl"}
  {/if}
{/block}
