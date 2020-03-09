<tr>
  <td class="small">{include "bits/userLink.tpl" u=$od->modUser}</td>
  <td class="small">{include 'bits/moment.tpl' t=$od->modDate}</td>

    {foreach $od->getTextChanges() as $diff}
      <td class="small text-right">
        {$diff.title}
      </td>
      <td class="small">
        {include "bits/diff/text.tpl" ses=$diff.ses}
      </td>
    {/foreach}
</tr>

{foreach $od->getFieldChanges() as $change}
  <tr>
    {include "bits/diff/field.tpl"
    type=$change.type
    title=$change.title
    old=$change.old
    new=$change.new}
  </tr>
{/foreach}

{if $od->review}
  <div class="alert alert-light">
    {include "bits/reviewFlagList.tpl" flags=$od->review->getFlags()}
  </div>
{/if}
