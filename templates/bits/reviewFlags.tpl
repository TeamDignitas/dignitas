<h3>{t}title-review-votes{/t}</h3>

<table class="table">
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
          {$f->getVoteName()}
        </td>

        <td>
          {$f->details|escape}
        </td>

        <td>{include 'bits/moment.tpl' t=$f->createDate}</td>
      </tr>
    {/foreach}
  </tbody>
</table>
