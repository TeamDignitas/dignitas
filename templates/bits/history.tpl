{extends "layout.tpl"}

{block "title"}{$title|escape}{/block}

{block "content"}
  <h1 class="statement-history mb-4">{$title|escape}</h1>

  <div class="container">
    <div class="row small py-1">
      <div class="col-sm-6 col-md-2 font-weight-bold pl-0">Author</div>
      <div class="col-sm-6 col-md-1 font-weight-bold pl-0">Date</div>
      <div class="d-none d-sm-none d-md-block col-md-9 font-weight-bold pl-0">Revision</div>
    </div>

    {foreach $history as $od}
      <div class="row small row-border py-1">
        {include "bits/diff/objectDiff.tpl"}
      </div>
    {/foreach}
  </div>
{/block}
