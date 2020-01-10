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

      {foreach $rec.dependantChanges as $change}
        {if count($change.objects)}
          <dt class="col-sm-3">
            {$change.title}
          </dt>
          <dd class="col-sm-9">
            {foreach $change.objects as $o}
              {assign var="{$change.param}" value=$o}
              {include $change.template}
            {/foreach}
          </dd>
        {/if}
      {/foreach}
    </dl>
  {/foreach}

{/block}
