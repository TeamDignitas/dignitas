{$showEntity=$showEntity|default:true}
<div>
  {foreach $statements as $i => $s}
    {$e=$s->getEntity()}
    <div class="statement card border-secondary mr-3 mb-3">

      <div class="card-body d-flex">

        <div class="flex-grow-1">
          <div class="pb-4 statement-link">
            {include "bits/statementLink.tpl" statement=$s quotes=false}
          </div>

          <div class="text-right small">
            {if $showEntity}
              {$entity=$s->getEntity()}
              — {include "bits/entityLink.tpl" e=$entity},
            {/if}
            {$s->dateMade|ld}
          </div>
        </div>

        {if $showEntity}
          <div class="pl-5">
            {include "bits/image.tpl"
              obj=$e
              geometry=Config::THUMB_ENTITY_STATEMENT_LIST
              imgClass="pic rounded-circle img-fluid no-outline"}
          </div>
        {/if}

      </div>
    </div>
  {/foreach}
</div>
