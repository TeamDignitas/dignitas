{* @param $n number of pages *}
{* @param $k current page *}
{$ends=Util::getPaginationRange($n, $k)}
<ul class="pagination pagination-sm justify-content-end">
  {* previous page *}
  <li class="page-item {if $k == 1}disabled{/if}">
    <a class="page-link" href="#" data-dest="{$k-1}">&lsaquo;</a>
  </li>

  {* page 1 and ellipsis *}
  {if $ends[0] > 1}
    <li class="page-item {if $k == 1}active{/if}">
      <a class="page-link" href="#">1</a>
    </li>
    <li class="page-item disabled">
      <a class="page-link" href="#">…</a>
    </li>
  {/if}

  {* range around $k *}
  {for $p = $ends[0] to $ends[1]}
    <li class="page-item {if $k == $p}active{/if}">
      <a class="page-link" href="#">{$p}</a>
    </li>
  {/for}

  {* ellipsis and page $n *}
  {if $ends[1] < $n}
    <li class="page-item disabled">
      <a class="page-link" href="#">…</a>
    </li>
    <li class="page-item {if $k == $n}active{/if}">
      <a class="page-link" href="#">{$n}</a>
    </li>
  {/if}

  {* next page *}
  <li class="page-item {if $k == $n}disabled{/if}">
    <a class="page-link" href="#" data-dest="{$k+1}">&rsaquo;</a>
  </li>
</ul>
