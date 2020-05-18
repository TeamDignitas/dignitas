<div class="statement-list card-columns">
  {foreach $statements as $s}
    <div class="statement card border-secondary mr-3 mb-3 py-4 px-4">
      {$entity=$s->getEntity()}

      <div class="">
        <div class="card-title">
          {include "bits/statementLink.tpl" statement=$s}
        </div>

        <div class="text-right card-text small">
          — {include "bits/entityLink.tpl" e=$entity},
          {$s->dateMade|ld}
        </div>

      </div>
    </div>
  {/foreach}
</div>
