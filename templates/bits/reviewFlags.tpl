<h3>{t}Flags in this review{/t}</h3>

<table class="table">
  <thead>
    <tr>
      <th>{t}user{/t}</th>
      <th>{t}reason{/t}</th>
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
          {if $f->reason == Flag::REASON_SPAM}
            {t}spam{/t}
          {elseif $f->reason == Flag::REASON_ABUSE}
            {t}abuse{/t}
          {elseif $f->reason == Flag::REASON_DUPLICATE}
            {t}duplicate of{/t}
            {include "bits/statementLink.tpl" statement=$f->getDuplicate()}
          {elseif $f->reason == Flag::REASON_OFF_TOPIC}
            {t}off-topic{/t}
          {elseif $f->reason == Flag::REASON_UNVERIFIABLE}
            {t}unverifiable{/t}
          {elseif $f->reason == Flag::REASON_LOW_QUALITY}
            {t}low quality{/t}
          {elseif $f->reason == Flag::REASON_OTHER}
            {t}other{/t}
            (<i>{$f->details|escape}</i>)
          {/if}
        </td>

        <td>{$f->createDate|moment}</td>
      </tr>
    {/foreach}
  </tbody>
</table>
