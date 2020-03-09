<div>

    {if $change.type == Ct::FIELD_CHANGE_TAG_LIST}

      {foreach $old as $t}
        {include "bits/tag.tpl"}
      {/foreach}

    {elseif $change.type == Ct::FIELD_CHANGE_LINK_LIST}

      {foreach $old as $l}
        {include "bits/link.tpl"}
      {/foreach}

    {elseif $change.type == Ct::FIELD_CHANGE_RELATION_LIST}

      {foreach $old as $r}
        {include "bits/relation.tpl" showEditLink=false}
      {/foreach}

    {elseif $change.type == Ct::FIELD_CHANGE_STRING_LIST}

      <ul class="list-inline list-inline-bullet">
        {foreach $old as $s}
          <li class="list-inline-item">{$s|escape}</li>
        {/foreach}
      </ul>

    {elseif $change.type == Ct::FIELD_CHANGE_COLOR}

      <span style="padding-left: 1em; background-color: {$old};"></span>
      &nbsp; {$old}
      <i class="icon icon-right-open"></i>
      <span style="padding-left: 1em; background-color: {$new};"></span>
      &nbsp; {$new}

    {else}

      <span class="diff-op2">{$old}</span>
      <i class="icon icon-right-open"></i>
      <span class="diff-op1">{$new}</span>

    {/if}
</div>
