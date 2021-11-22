{$link=$link|default:false}
{$colors=$colors|default:true}
{$tooltip=$tooltip|default:''}

{$style="color: {$t->getColor()}; background: {$t->getBackground()};"}

{strip}
<span class="tag me-1 {if $t->tooltip}tag-tooltip{/if}" title="{$t->tooltip}">
  <a
    href="{$t->getViewUrl()}"
    class="badge rounded-pill {if !$link}disabled{/if} py-1 px-2"
    {if !$link} disabled tabindex="-1"{/if}
    {if $colors} style="{$style}"{/if}
    {if $tooltip} title="{$tooltip}"{/if}>
    {if $t->icon}
      {include "bits/icon.tpl" i=$t->icon}
    {/if}
    {if $t->icon && !$t->iconOnly}
      &nbsp;
    {/if}
    {if !$t->iconOnly}
      {$t->value}
    {/if}
  </a>
</span>
{/strip}
