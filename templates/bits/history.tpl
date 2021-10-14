{extends "layout.tpl"}

{block "title"}{$title|escape}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="history mb-4">{$title|escape}</h1>

    {if empty($history)}
      {t}info-no-history{/t}
    {else}
      <table class="table table-hover">
        <thead>
          <tr class="d-flex small">
            <th class="col-sm-3 col-md-2">{t}label-author{/t}</th>
            <th class="col-sm-2 col-md-2">{t}label-date{/t}</th>
            <th class="col-sm-7 col-md-8">{t}label-changes{/t}</th>
          </tr>
        </thead>

        <tbody>
          {foreach $history as $od}
            <tr class="d-flex small">
              {include "bits/diff/objectDiff.tpl"}
            </tr>
          {/foreach}
        </tbody>
      </table>
    {/if}

    <div class="mt-3">
      <a href="{$backButtonUrl}" class="btn btn-sm btn-primary">
        {include "bits/icon.tpl" i=arrow_back}
        {$backButtonText}
      </a>
    </div>
  </div>
{/block}
