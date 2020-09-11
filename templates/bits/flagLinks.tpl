{* Mandatory argument: $obj *}
{$flagLinks=$flagLinks|default:true} {* pages can request the absence of flag links *}
{$class=$class|default:''}

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
    {if $obj->isFlagged()}hidden{/if}
    title="{t}link-flag{/t}">
    <i class="icon icon-flag"></i>
  </a>

  <a
    id="{$unflagId}"
    href="#"
    class="unflag {$class}"
    data-object-type="{$obj->getObjectType()}"
    data-object-id="{$obj->id}"
    data-flag-link="#{$flagId}"
    {if !$obj->isFlagged()}hidden{/if}
    title="{t}link-unflag{/t}">
    <i class="icon icon-flag-empty"></i>
  </a>
{/if}
