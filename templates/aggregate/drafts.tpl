{extends "layout.tpl"}

{block "title"}{cap}{t}title-my-drafts{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-my-drafts{/t}{/cap}</h1>

    {if count($statementDrafts)}
      <h4 class="mt-5">{cap}{t}label-statements{/t}{/cap}</h4>

      {include "bits/statementList.tpl" statements=$statementDrafts}
    {/if}

    {if count($answerDrafts)}
      <h4 class="mt-5">{cap}{t}label-answers{/t}{/cap}</h4>

      {foreach $answerDrafts as $a}
        <div class="statement card mb-3">
          <div class="card-body">
            {$a->contents|md}
          </div>

          <div class="card-footer">
            <a href="{$a->getEditUrl()}" class="btn btn-sm btn-outline-secondary">
              {include "bits/icon.tpl" i=mode_edit}
              {t}link-edit{/t}
            </a>
          </div>
        </div>
      {/foreach}
    {/if}
  </div>
{/block}
