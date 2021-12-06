{extends "layout.tpl"}

{block "title"}{$title|escape}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="history mb-4">{$title|escape}</h1>

    {if empty($history)}
      {t}info-no-history{/t}
    {else}
      <div class="gtable container">
        <div class="row gtable-header">
          <div class="col-6">{t}label-author{/t}</div>
          <div class="col-6">{t}label-date{/t}</div>
          <div class="col-12">{t}label-changes{/t}</div>
        </div>

        {foreach $history as $od}
          {include "bits/diff/objectDiff.tpl"}
        {/foreach}
      </div>
    {/if}

    <div class="mt-3">
      <a href="{$backButtonUrl}" class="btn btn-sm btn-primary">
        {include "bits/icon.tpl" i=arrow_back}
        {$backButtonText}
      </a>
    </div>
  </div>
{/block}
