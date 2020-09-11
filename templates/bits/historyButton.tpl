{* history button for objects that may have revisions *}
{* mandatory argument: $obj *}
{$class=$class|default:"btn btn-outline-secondary mt-1"}

{if $obj->hasRevisions()}
  <a
    href="{$obj->getHistoryUrl()}"
    class="{$class}"
    title="{t}link-show-revisions{/t}">
    <i class="icon icon-hourglass"></i>
  </a>
{/if}
