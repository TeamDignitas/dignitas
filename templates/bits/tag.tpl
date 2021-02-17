{$link=$link|default:false}
{$colors=$colors|default:true}
{$tooltip=$tooltip|default:''}

{$style="color: {$t->getColor()}; background: {$t->getBackground()};"}

{strip}
<span class="tag mr-1 {if $t->tooltip}tag-tooltip{/if}" title="{$t->tooltip}">
  <a
    href="{Router::link('tag/view')}/{$t->id}"
    class="badge badge-pill badge-default {if !$link}disabled{/if}"
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
