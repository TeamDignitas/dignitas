{* Mandatory argument: $obj *}
{$class=$class|default:'btn mt-1'}

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
    title="{t}info-subscribe{/t}">
    {include "bits/icon.tpl" i=visibility class=""}
    {t}link-subscribe{/t}
  </a>

  <a
    id="{$unsubscribeId}"
    href="#"
    class="unsubscribe {$class}"
    data-object-type="{$obj->getObjectType()}"
    data-object-id="{$obj->id}"
    data-subscribe-link="#{$subscribeId}"
    {if !Subscription::exists($obj)}hidden{/if}
    title="{t}info-unsubscribe{/t}">
    {include "bits/icon.tpl" i=visibility_off class=""}
    {t}link-unsubscribe{/t}
  </a>
{/if}
