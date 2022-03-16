<div class="mt-4">
  {foreach $results as $tuple}
    <h6 class="capitalize-first-word mt-4">
      {$tuple.relationType->name|esc}
      {include "bits/entityLink.tpl" e=$tuple.toEntity phrase=$tuple.relationType->phrase}
    </h6>
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
