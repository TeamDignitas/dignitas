{extends "layout.tpl"}

{block "title"}{$title|esc}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="history mb-4">{$title|esc}</h1>

    {if empty($history)}
      {t}info-no-history{/t}
    {else}

      <div class="table-responsive">
        <table class="table dtable">

          <thead>
            <tr>
              <th>{t}label-author{/t}</th>
              <th>{t}label-date{/t}</th>
              <th>{t}label-changes{/t}</th>
            </tr>
          </thead>

          <tbody>
            {foreach $history as $od}
              {include "bits/diff/objectDiff.tpl"}
            {/foreach}
          </tbody>

        </table>
      </div>

    {/if}

    <div class="mt-3">
      <a href="{$backButtonUrl}" class="btn btn-sm btn-primary">
        {$backButtonText}
      </a>
    </div>
  </div>
{/block}
