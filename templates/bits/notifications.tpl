{foreach $notifications as $n}
  <div class="row row-border small py-1 ml-1">
    <div class="col-sm-4 col-lg-2 text-muted">
      {$n->createDate|lt:false}
    </div>
    <div class="col-sm-8 col-lg-10">
      {* Get the notification object. Note that it may have been deleted. *}
      {$obj=$n->getObject()}

      {if $obj}
        {* Get the actual target, in case it was delegated. It, too, may have been deleted. *}
        {if $n->type == Subscription::TYPE_NEW_ANSWER ||
          $n->type == Subscription::TYPE_NEW_COMMENT}
          {$target=$obj->getObject()}
        {else}
          {$target=$obj}
        {/if}

        {if $target}
          {if $target->getObjectType() == Proto::TYPE_STATEMENT}
            {$text=$target->summary}
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
