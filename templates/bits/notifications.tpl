<div class="gtable container">

  <div class="row gtable-header d-flex">
    <div class="col-10 col-lg-2">{t}label-date{/t}</div>
    <div class="col-2 col-lg-1 text-end order-lg-last">{t}label-action{/t}</div>
    <div class="col-12 col-lg-9">{t}label-event{/t}</div>
  </div>

  {foreach $notifications as $n}
    <div class="row gtable-row">
      <div class="col-10 col-lg-2 text-muted text-nowrap">
        {$n->createDate|lt:false}
      </div>
      <div class="col-2 col-lg-1 text-end order-lg-last">
        <a
          href="#"
          class="btn notification-unsubscribe"
          title="{t}info-notification-unsubscribe{/t}"
          data-notification-id="{$n->id}">
          {include "bits/icon.tpl" i=visibility_off}
        </a>
      </div>
      <div class="col-12 col-lg-9">
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
    </div>
  {/foreach}
</div>
