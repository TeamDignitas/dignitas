{foreach $notifications as $n}
  <div class="row notification small py-1 ms-1">
    <div class="col-sm-3 col-lg-2 text-muted">
      {$n->createDate|lt:false}
    </div>
    <div class="col-sm-8 col-lg-9">
      {* Get the notification object. Note that it may have been deleted. *}
      {$obj=$n->getObject()}

      {if $obj}
        {* Get the actual target, in case it was delegated. It, too, may have been deleted. *}
        {if $n->type == Notification::TYPE_NEW_ANSWER}
          {$target=$obj->getStatement()}
        {else if $n->type == Notification::TYPE_NEW_COMMENT}
          {$target=$obj->getObject()}
        {else}
          {$target=$obj}
        {/if}

        {if $target}
          {if $target->getObjectType() == Proto::TYPE_STATEMENT}
            {$text=$target->summary}
          {elseif $target->getObjectType() == Proto::TYPE_ENTITY}
            {$text=$target->name}
          {elseif $target->getObjectType() == Proto::TYPE_USER}
            {$text=$target|escape}
          {else}
            {$text=$target->contents}
          {/if}

          {* Actually print the notification link and text *}
          {$n->getTypeName()}
          <a href="{$obj->getViewUrl()}">
            {$text|shorten:120}
          </a>
        {/if}
      {/if}
    </div>
    <div class="col-sm-1 col-lg-1 text-end">
      <a
        href="#"
        class="btn notification-unsubscribe"
        title="{t}info-notification-unsubscribe{/t}"
        data-notification-id="{$n->id}">
        {include "bits/icon.tpl" i=visibility_off}
      </a>
    </div>
  </div>
{/foreach}
