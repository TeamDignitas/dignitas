{* Mandatory argument: $obj *}
{$class=$class|default:'btn btn-outline-secondary mt-1'}

{if User::getActive()}
  {$subscribeId="subscribe_{$obj->getObjectType()}_{$obj->id}"}
  {$unsubscribeId="unsubscribe_{$obj->getObjectType()}_{$obj->id}"}

  <a
    id="{$subscribeId}"
    href="#"
    class="subscribe {$class}"
    data-object-type="{$obj->getObjectType()}"
    data-object-id="{$obj->id}"
    data-unsubscribe-link="#{$unsubscribeId}"
    {if Subscription::exists($obj)}hidden{/if}
    title="{t}link-subscribe{/t}">
    <i class="icon icon-eye"></i>
  </a>

  <a
    id="{$unsubscribeId}"
    href="#"
    class="unsubscribe {$class}"
    data-object-type="{$obj->getObjectType()}"
    data-object-id="{$obj->id}"
    data-subscribe-link="#{$subscribeId}"
    {if !Subscription::exists($obj)}hidden{/if}
    title="{t}link-unsubscribe{/t}">
    <i class="icon icon-eye-off"></i>
  </a>
{/if}
