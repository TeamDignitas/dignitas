<h4 class="revisionHeader">
  {if $od->review}
    {t}changes suggested by{/t}
  {else}
    {t}changes by{/t}
  {/if}
  {include "bits/userLink.tpl" u=$od->modUser}
  {include 'bits/moment.tpl' t=$od->modDate}
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

{if $od->review}
  <div class="alert alert-light">
    {include "bits/reviewFlagList.tpl" flags=$od->review->getFlags()}
  </div>
{/if}

<hr>
