{$showEntity=$showEntity|default:true}
<div class="statement-list card-columns">
  {foreach $statements as $s}
    <div class="statement card border-secondary mr-3 mb-3 py-4 px-4">

      <div class="card-title">
        {include "bits/statementLink.tpl" statement=$s}
      </div>

      <div class="text-right card-text small">
        {if $showEntity}
          {$entity=$s->getEntity()}
          — {include "bits/entityLink.tpl" e=$entity},
        {/if}
        {$s->dateMade|ld}
      </div>
    </div>
  {/foreach}
</div>
