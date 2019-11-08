<h3>{t}Votes in this review{/t}</h3>

<table class="table">
  <thead>
    <tr>
      <th>{t}user{/t}</th>
      <th>{t}vote{/t}</th>
      {if $review->reason == Review::REASON_OTHER}
        <th>{t}details{/t}</th>
      {/if}
      <th>{t}date{/t}</th>
    </tr>
  </thead>

  <tbody>
    {foreach $review->getFlags() as $f}
      <tr>
        <td>
          {include "bits/userLink.tpl" u=$f->getUser()}
        </td>

        <td>
          {$f->getVoteName()}
        </td>

        {if $review->reason == Review::REASON_OTHER}
          <td>
            {$f->details|escape}
          </td>
        {/if}

        <td>{$f->createDate|moment}</td>
      </tr>
    {/foreach}
  </tbody>
</table>
