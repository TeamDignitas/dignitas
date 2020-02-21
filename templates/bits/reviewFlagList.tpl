<ul>
  {foreach $flags as $f}
    <li>
      {include "bits/userLink.tpl" u=$f->getUser()}

      {if $f->vote == Flag::VOTE_KEEP}
        {t}review-voted-keep{/t}
      {else}
        {t}review-voted-remove{/t}
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
