{* history button for objects that may have revisions *}
{* mandatory argument: $obj *}
{$class=$class|default:"btn mt-1"}
{$icon=$icon|default:true}
{$iconOnly=$iconOnly|default:false}

{if $obj->hasRevisions()}
  <a
    href="{$obj->getHistoryUrl()}"
    class="{$class}"
    {if $iconOnly}title="{t}link-show-revisions{/t}"{/if}
  >

    {if $icon}
      {* override $class from before *}
      {include "bits/icon.tpl" i=hourglass_full class=""}
    {/if}

    {if !$iconOnly}
      {t}link-show-revisions{/t}
    {/if}
  </a>
{/if}
