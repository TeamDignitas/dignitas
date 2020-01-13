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
  {else}
    <span class="diffOp2">{$old}</span>
    <i class="icon icon-right-open"></i>
    <span class="diffOp1">{$new}</span>
  {/if}
</dd>
