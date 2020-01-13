{extends "layout.tpl"}

{block "title"}
  {t}Statement history for{/t}:
  {cap}{$statement->summary|escape}{/cap}
{/block}

{block "content"}
  {foreach $history as $od}
    <h4 class="versionHeader">
      {t}changes by{/t}
      {include "bits/userLink.tpl" u=$od->modUser}
      {$od->modDate|lt:false:true}
    </h4>

    {foreach $od->getTextChanges() as $diff}
      {include "bits/diff/card.tpl"
        title=$diff.title
        ses=$diff.ses}
    {/foreach}

    <dl class="row">
      {foreach $od->getFieldChanges() as $change}
        {include "bits/diff/field.tpl"
          type=$change.type
          title=$change.title
          old=$change.old
          new=$change.new}
      {/foreach}
    </dl>
  {/foreach}

{/block}