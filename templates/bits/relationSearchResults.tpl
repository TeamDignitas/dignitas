<div class="mt-4">
  {foreach $results as $tuple}
    <h5>
      {$tuple.relationType->name|escape}
      {include "bits/entityLink.tpl" e=$tuple.toEntity phrase=$tuple.relationType->phrase}
    </h5>
    <ul>
      {foreach $tuple.data as $rec}
        <li {if $rec.relation->ended()}class="text-muted"{/if}>
          {include "bits/entityLink.tpl" e=$rec.fromEntity}
          {$rec.relation->getDateRangeString()}
        </li>
      {/foreach}
    </ul>
  {/foreach}
</div>
