{extends "layout.tpl"}

{block "title"}{$title|escape}{/block}

{block "content"}
  <h3>{$title|escape}</h3>
  {foreach $history as $od}
    {include "bits/diff/objectDiff.tpl"}
  {/foreach}

{/block}
