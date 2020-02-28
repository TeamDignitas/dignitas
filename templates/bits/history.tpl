{extends "layout.tpl"}

{block "title"}{$title|escape}{/block}

{block "content"}
  <h3>{$title|escape}</h3>

  {foreach $history as $od}
    {include "bits/diff/objectDiff.tpl"}
  {/foreach}

  <div class="mt-2">
    <a
      class="btn btn-sm btn-outline-secondary"
      href="javascript:history.back()">
      <i class="icon icon-left"></i>
      {t}link-go-back{/t}
    </a>
  </div>

{/block}
