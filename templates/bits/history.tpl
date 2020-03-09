{extends "layout.tpl"}

{block "title"}{$title|escape}{/block}

{block "content"}
  <h1 class="statement-history mb-4">{$title|escape}</h1>

  <table class="table table-hover revisions-table">
    <thead>
      <tr>
        <th scope="col">Author</th>
        <th scope="col">Date</th>
        <th scope="col">Type of revision</th>
        <th scope="col">Revision description</th>
      </tr>
    </thead>
    <tbody>
      {foreach $history as $od}
        {include "bits/diff/objectDiff.tpl"}
      {/foreach}
    </tbody>
  </table>
{/block}
