{extends "layout.tpl"}

{block "title"}{t}tag{/t}: {$tag->value}{/block}

{block "content"}
  <h3>{t}tag{/t}: {$tag->value}</h3>

  {include "bits/tagAncestors.tpl"}

  {if User::may(User::PRIV_EDIT_TAG)}
    <p>
      <a href="{Router::link('tag/edit')}/{$tag->id}" class="btn btn-light">
        <i class="icon icon-edit"></i>
        {t}edit{/t}
      </a>
    </p>
  {/if}

  {if count($statements)}
    <h3>
      {if $statementCount > count($statements)}
        {t 1=count($statements) 2=$statementCount}
        statements (%1 of %2 shown)
        {/t}
      {else}
        {t 1=count($statements)}
        statements (%1)
        {/t}
      {/if}
    </h3>

    {include "bits/statementList.tpl"}
  {/if}
{/block}
