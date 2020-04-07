<h4 class="mt-5 capitalize-first-word">{t}title-review-votes{/t}</h4>

<table class="table table-sm mb-5">
  <thead>
    <tr>
      <th>{t}user{/t}</th>
      <th>{t}vote{/t}</th>
      <th>{t}details{/t}</th>
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
