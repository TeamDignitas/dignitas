<ul>
  {foreach $flags as $f}
    <li>
      {include "bits/userLink.tpl" u=$f->getUser()}

      {if $f->vote == Flag::VOTE_KEEP}
        {t}voted to keep{/t}
      {else}
        {t}voted to remove{/t}
      {/if}

      ({$f->getWeightName()})

      {include 'bits/moment.tpl' t=$f->createDate}

      {if $f->details}
        <br>
        {$f->details|escape}
      {/if}
    </li>
  {/foreach}
</ul>
