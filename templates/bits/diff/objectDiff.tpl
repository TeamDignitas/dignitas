<div class="col-sm-6 col-md-2 pl-0 highlight-sm">{include "bits/userLink.tpl" u=$od->modUser}</div>
<div class="col-sm-6 col-md-1 pl-0 highlight-sm">{include 'bits/moment.tpl' t=$od->modDate}</div>

<div class="col-sm-11 offset-sm-1 col-md-9 offset-md-0">
  <div class="row">
    {foreach $od->getTextChanges() as $diff}
      <div class="col-md-2 pl-0">- {$diff.title}</div>
      <div class="col-md-10">
        {include "bits/diff/text.tpl" ses=$diff.ses}
      </div>
    {/foreach}

    {foreach $od->getFieldChanges() as $change}
      <div class="col-md-2 pl-0">- {$change.title}</div>
      <div class="col-md-10">
        {include "bits/diff/field.tpl"
        type=$change.type
        old=$change.old
        new=$change.new}
      </div>
    {/foreach}
  </div>
</div>

{if $od->review}
  <div class="alert alert-light">
    {include "bits/reviewFlagList.tpl" flags=$od->review->getFlags()}
  </div>
{/if}
