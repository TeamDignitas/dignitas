{$link=$link|default:false}
{$colors=$colors|default:true}

{$style="color: {$t->getColor()}; background: {$t->getBackground()};"}

{strip}
<span class="tag {if $t->tooltip}tagTooltip{/if}" title="{$t->tooltip}">
  <a
    href="{Router::link('tag/view')}/{$t->id}"
    class="badge badge-pill badge-default {if !$link}disabled{/if}"
    {if !$link} disabled tabindex="-1"{/if}
    {if $colors} style="{$style}"{/if}>
    {if $t->icon}
      <i class="icon icon-{$t->icon}"></i>
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
