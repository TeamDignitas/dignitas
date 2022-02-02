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
        <li class="list-inline-item">{$s|esc}</li>
      {/foreach}
    </ul>

  {elseif $change.type == Ct::FIELD_CHANGE_COLOR}

    <span style="padding-left: 1em; background-color: {$old};"></span>
    &nbsp; {$old}
    {include "bits/icon.tpl" i=chevron_right}
    <span style="padding-left: 1em; background-color: {$new};"></span>
    &nbsp; {$new}

  {elseif $change.type == Ct::FIELD_CHANGE_BOOLEAN}

    {$new}

  {else}

    <span class="diff-op2">{$old}</span>
    {include "bits/icon.tpl" i=chevron_right}
    <span class="diff-op1">{$new}</span>

  {/if}
</div>
