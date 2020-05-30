{* @param $n number of pages *}
{* @param $k current page *}
{* @param $url URL to call for the page contents *}
{* @param $target element to overwrite with the page contents *}

{* When we print the initial page we include this template. Subsequent *}
{* refreshes via Ajax will include only pagination.tpl. *}
{if $n > 1}
  <div
    class="pagination-wrapper"
    data-url="{$url}"
    data-target="{$target}"
    data-num-pages="{$n}">

    {include "bits/pagination.tpl"}
  </div>
{/if}
