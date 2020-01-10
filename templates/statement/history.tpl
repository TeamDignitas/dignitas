{extends "layout.tpl"}

{block "title"}
  {t}Statement history for{/t}:
  {cap}{$statement->summary|escape}{/cap}
{/block}

{block "content"}
  {foreach $history as $rec}
    <h4 class="versionHeader">
      {t}changes by{/t}
      {include "bits/userLink.tpl" u=$rec.modUser}
      {$rec.modDate|lt:false:true}
    </h4>

    {foreach $rec.textDiffs as $diff}
      {include "bits/diff/card.tpl"
        title=$diff.title
        ses=$diff.ses}
    {/foreach}

    <dl class="row">
      {foreach $rec.fieldChanges as $change}
        {include "bits/diff/field.tpl"
          title=$change.title
          old=$change.old
          new=$change.new}
      {/foreach}
    </dl>

    {if count($rec.tagChanges.added)}
      {t}added tags{/t}
      {foreach $tagChanges.added as $t}
        {include "bits/tag.tpl"}
      {/foreach}
    {/if}
    {if count($rec.tagChanges.deleted)}
      {t}deleted tags{/t}
      {foreach $tagChanges.deleted as $t}
        {include "bits/tag.tpl"}
      {/foreach}
    {/if}
  {/foreach}

{/block}
