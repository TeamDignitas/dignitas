{* Mandatory argument: $obj *}
{$flagLinks=$flagLinks|default:true} {* pages can request the absence of flag links *}

{if $flagLinks && ($obj->isFlaggable() || $obj->isFlagged())}
  {$flagId="flag_{$obj->getObjectType()}_{$obj->id}"}
  {$unflagId="unflag_{$obj->getObjectType()}_{$obj->id}"}

  <a
    id="{$flagId}"
    href="#"
    class="dropdown-item"
    data-bs-toggle="modal"
    data-bs-target="#modal-flag"
    data-object-type="{$obj->getObjectType()}"
    data-object-id="{$obj->id}"
    data-unflag-link="#{$unflagId}"
    {if $obj->isFlagged()}hidden{/if}>
    {include "bits/icon.tpl" i=flag class=""}
    {t}link-flag{/t}
  </a>

  <a
    id="{$unflagId}"
    href="#"
    class="unflag dropdown-item"
    data-object-type="{$obj->getObjectType()}"
    data-object-id="{$obj->id}"
    data-flag-link="#{$flagId}"
    {if !$obj->isFlagged()}hidden{/if}>
    {* we could have a different icon for cancelling a flag *}
    {include "bits/icon.tpl" i=flag class=""}
     {t}link-unflag{/t}
  </a>
{/if}
