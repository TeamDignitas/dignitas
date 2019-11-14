<ul>
  {foreach $answer->getReviewFlags() as $f}
    <li>
      {include "bits/userLink.tpl" u=$f->getUser()}

      {if $f->vote}
        {t}voted to remove{/t}
      {else}
        {t}voted to keep{/t}
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
