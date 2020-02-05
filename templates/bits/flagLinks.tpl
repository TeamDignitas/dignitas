{* Mandatory argument: $obj *}
{* Optional argument: $class *}
{$class=$class|default:''}
{$tiny=$tiny|default:false}

{$flagId="flag_{$obj->getObjectType()}_{$obj->id}"}
{$unflagId="unflag_{$obj->getObjectType()}_{$obj->id}"}

<a
  id="{$flagId}"
  href="#"
  class="{$class}"
  data-toggle="modal"
  data-target="#flagModal"
  data-object-type="{$obj->getObjectType()}"
  data-object-id="{$obj->id}"
  data-unflag-link="#{$unflagId}"
  {if $obj->isFlagged()}hidden{/if}>
  <i class="icon icon-flag"></i>
  {if !$tiny}
    {t}flag{/t}
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
  {if !$tiny}
    {t}unflag{/t}
  {/if}
</a>
