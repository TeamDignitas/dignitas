{if $review}
  {$flags=$review->getFlags()}
  {foreach $flags as $f}
    <ul class="list-inline list-inline-bullet mb-0">
      <li class="list-inline-item">
        <b>{$review->getVoteName($f->vote)}</b>
      </li>

      <li class="list-inline-item">
        {include "bits/userLink.tpl" u=$f->getUser()}
      </li>

      <li class="list-inline-item">
        {include 'bits/moment.tpl' t=$f->createDate}
      </li>

      <li class="list-inline-item">
        {$f->getWeightName()}
      </li>
    </ul>

    {if $f->details}
      <div class="ps-3">
        {$f->details|esc}
      </div>
    {/if}
  {/foreach}
{/if}
