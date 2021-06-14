{* an input group consisting of an icon plus a field *}
{* mandatory args: $name *}
{$autofocus=$autofocus|default:null}
{$icon=$icon|default:null} {* can be empty in tag/edit.tpl *}
{$ifErrors=$ifErrors|default:null}
{$mb=$mb|default:3}
{$placeholder=$placeholder|default:null}
{$type=$type|default:'text'}
{$value=$value|default:null}

<div class="input-group mb-{$mb}">

  {if $icon}
    {include "bits/icon.tpl" i=$icon class="input-group-text"}
  {/if}

  <input
    {if $autofocus}autofocus{/if}
    class="form-control {if $ifErrors}is-invalid{/if}"
    name="{$name}"
    {if $placeholder}placeholder="{$placeholder|default}"{/if}
    type="{$type}"
    {if $value}value="{$value|escape}"{/if}>

  {if $ifErrors}
    {include "bs/feedback.tpl" errors=$ifErrors}
  {/if}

</div>
