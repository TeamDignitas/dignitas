<ul>
  {foreach $answer->getReviewFlags() as $f}
    <li>
      {include "bits/userLink.tpl" u=$f->getUser()}

      {if $f->vote == Flag::VOTE_KEEP}
        {t}voted to keep{/t}
      {else}
        {t}voted to remove{/t}
      {/if}

      {if $f->weight}
        ({t}executive{/t})
      {else}
        ({t}advisory{/t})
      {/if}

      {$f->createDate|moment}.
    </li>
  {/foreach}
</ul>
