{* Mandatory argument: $obj *}
{$flagLinks=$flagLinks|default:true} {* pages can request the absence of flag links *}
{$class=$class|default:''}
{$iconOnly=$iconOnly|default:false}

{if $flagLinks && ($obj->isFlaggable() || $obj->isFlagged())}
  {$flagId="flag_{$obj->getObjectType()}_{$obj->id}"}
  {$unflagId="unflag_{$obj->getObjectType()}_{$obj->id}"}

  <a
    id="{$flagId}"
    href="#"
    class="{$class}"
    data-toggle="modal"
    data-target="#modal-flag"
    data-object-type="{$obj->getObjectType()}"
    data-object-id="{$obj->id}"
    data-unflag-link="#{$unflagId}"
    {if $obj->isFlagged()}hidden{/if}>
    <i class="icon icon-flag"></i>

    {if !$iconOnly}
      {t}link-flag{/t}
    {/if}
  </a>

  <a
    id="{$unflagId}"
    href="#"
    class="unflag {$class}"
    data-object-type="{$obj->getObjectType()}"
    data-object-id="{$obj->id}"
    data-flag-link="#{$flagId}"
    {if !$obj->isFlagged()}hidden{/if}>
    <i class="icon icon-flag-empty"></i>
    {if !$iconOnly}
      {t}link-unflag{/t}
    {/if}
  </a>
{/if}
