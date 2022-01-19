<div class="table-responsive">
  <table class="table dtable">

    <thead>
      <tr>
        <th>{t}label-date{/t}</th>
        <th>{t}label-event{/t}</th>
        <th>{t}label-action{/t}</th>
      </tr>
    </thead>

    <tbody>
      {foreach $notifications as $n}
        <tr>
          <td class="text-muted text-nowrap">
            {$n->createDate|lt:false}
          </td>
          <td>
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
          <td class="text-end">
            <a
              href="#"
              class="btn btn-sm notification-unsubscribe"
              title="{t}info-notification-unsubscribe{/t}"
              data-notification-id="{$n->id}">
              {include "bits/icon.tpl" i=visibility_off}
            </a>
          </td>
        </tr>
      {/foreach}
    </tbody>

  </table>
</div>
