{* history button for objects that may have revisions *}
{* mandatory argument: $obj *}
{$class=$class|default:"btn mt-1"}
{$iconOnly=$iconOnly|default:false}

{if $obj->hasRevisions()}
  <a
    href="{$obj->getHistoryUrl()}"
    class="{$class}">
    <i class="icon icon-hourglass"></i>

    {if !$iconOnly}
      {t}link-show-revisions{/t}
    {/if}
  </a>
{/if}
