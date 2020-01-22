{extends "layout.tpl"}

{block "title"}{$title|escape}{/block}

{block "content"}
  {foreach $history as $od}
    {include "bits/diff/objectDiff.tpl"}
  {/foreach}

{/block}
