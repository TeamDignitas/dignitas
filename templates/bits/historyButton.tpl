{* history button for objects that may have revisions *}
{* mandatory argument: $obj *}
{$class=$class|default:"btn mt-1"}
{$iconOnly=$iconOnly|default:false}

{if $obj->hasRevisions()}
  <a
    href="{$obj->getHistoryUrl()}"
    class="{$class}">

    {* override $class from before *}
    {include "bits/icon.tpl" i=hourglass_full class=""}

    {if !$iconOnly}
      {t}link-show-revisions{/t}
    {/if}
  </a>
{/if}
