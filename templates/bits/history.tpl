{extends "layout.tpl"}

{block "title"}{$title|escape}{/block}

{block "content"}
  <div class="container mt-4">
    <h1 class="history mb-4">{$title|escape}</h1>

    <div class="container">
      <div class="row py-1">
        <div class="col-sm-6 col-md-2 font-weight-bold pl-0">
          {t}label-author{/t}
        </div>
        <div class="col-sm-6 col-md-2 font-weight-bold pl-0">
          {t}label-date{/t}
        </div>
        <div class="d-none d-sm-none d-md-block col-md-8 font-weight-bold pl-0">
          {t}label-changes{/t}
        </div>
      </div>

      {foreach $history as $od}
        <div class="row small row-border py-1">
          {include "bits/diff/objectDiff.tpl"}
        </div>
      {/foreach}
    </div>
  </div>
{/block}
