{foreach $flags as $f}
  <div class="mb-1">
    {include "bits/userLink.tpl" u=$f->getUser()}

    {if $f->vote == Flag::VOTE_KEEP}
      {t}review-voted-keep{/t}
    {else}
      {t}review-voted-remove{/t}
    {/if}

    ({$f->getWeightName()})

    {include 'bits/moment.tpl' t=$f->createDate}

    {if $f->details}
      <div class="pl-3">
        {$f->details|escape}
      </div>
    {/if}
  </div>
{/foreach}
