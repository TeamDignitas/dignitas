<table class="table table-hover">
  <thead>
    <tr class="small">
      <th class="col-xs-12 col-sm-3 col-md-2 py-1">{t}label-date{/t}</th>
      <th class="col-xs-12 col-sm-8 col-md-9 py-1">{t}label-event{/t}</th>
      <th class="col-xs-12 col-sm-1 col-md-1 py-1">{t}label-action{/t}</th>
    </tr>
  </thead>

  <tbody>
    {foreach $notifications as $n}
      <tr class="small ms-0">
        <td class="col-sm-3 col-md-2 text-muted">
          {$n->createDate|lt:false}
        </td>
        <td class="col-sm-8 col-md-9">
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
        </td>
        <td class="col-sm-1 text-end">
          <a
            href="#"
            class="btn notification-unsubscribe"
            title="{t}info-notification-unsubscribe{/t}"
            data-notification-id="{$n->id}">
            {include "bits/icon.tpl" i=visibility_off}
          </a>
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>
