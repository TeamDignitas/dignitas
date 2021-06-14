{* mandatory args: $name, $label *}
{$cbErrors=$cbErrors|default:null}
{$checked=$checked|default:false}
{$divClass=$divClass|default:''}
{$help=$help|default:false}
{$inputId=$inputId|default:null}

{if !$inputId}
  {assign var=SEQUENTIAL_CB_ID value=1+{$SEQUENTIAL_CB_ID|default:0} scope="global"}
  {$inputId="cb-{$SEQUENTIAL_CB_ID}"}
{/if}

<div class="form-check {$divClass}">

  <input
    id="{$inputId}"
    type="checkbox"
    class="form-check-input {if $cbErrors}is-invalid{/if}"
    {if $name}name="{$name}"{/if}
    {if $checked}checked{/if}>

  <label for="{$inputId}" class="form-check-label">
    {$label}
  </label>

  {if $help}
    <div class="form-text">{$help}</div>
  {/if}

  {if $cbErrors}
    {include "bits/fieldErrors.tpl" errors=$cbErrors}
  {/if}

</div>
