<dt class="col-sm-3">{$title}</dt>
<dd class="col-sm-9">

  {if $change.type == Ct::FIELD_CHANGE_TAG_LIST}

    {foreach $old as $t}
      {include "bits/tag.tpl"}
    {/foreach}

  {elseif $change.type == Ct::FIELD_CHANGE_URL_LIST}

    {foreach $old as $s}
      {include "bits/statementSource.tpl"}
    {/foreach}

  {elseif $change.type == Ct::FIELD_CHANGE_RELATION_LIST}

    {foreach $old as $r}
      {include "bits/relation.tpl" showSourceLink=false}
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

    <span class="diffOp2">{$old}</span>
    <i class="icon icon-right-open"></i>
    <span class="diffOp1">{$new}</span>

  {/if}

</dd>
