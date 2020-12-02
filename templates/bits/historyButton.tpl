{* history button for objects that may have revisions *}
{* mandatory argument: $obj *}
{$class=$class|default:"btn mt-1"}

{if $obj->hasRevisions()}
  <a
    href="{$obj->getHistoryUrl()}"
    class="{$class}">
    <i class="icon icon-hourglass"></i>
    {t}link-show-revisions{/t}
  </a>
{/if}
