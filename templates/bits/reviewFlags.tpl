{$flags=$review->getFlags()}

{if count($flags)}
  <h3 class="my-5 capitalize-first-word">{t}title-review-votes{/t}</h3>

  <table class="table table-sm mb-5">
    <thead>
      <tr>
        <th class="border-0">{t}user{/t}</th>
        <th class="border-0">{t}vote{/t}</th>
        <th class="border-0">{t}details{/t}</th>
        <th class="border-0">{t}date{/t}</th>
      </tr>
    </thead>

    <tbody>
      {foreach $flags as $f}
        <tr>
          <td>
            {include "bits/userLink.tpl" u=$f->getUser()}
          </td>

          <td>
            {$review->getVoteName($f->vote)} ({$f->getWeightName()})
          </td>

          <td>
            {$f->details|escape}
          </td>

          <td>{include 'bits/moment.tpl' t=$f->createDate}</td>
        </tr>
      {/foreach}
    </tbody>
  </table>
{/if}
