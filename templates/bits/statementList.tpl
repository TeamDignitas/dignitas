{$showEntity=$showEntity|default:true}
<div class="mt-4">
  {foreach $statements as $i => $s}
    {$e=$s->getEntity()}
    <div class="statement card surface verdict-{$s->verdict} mb-3">

      <div class="card-body row">

        <div class="col-12 {if $showEntity}col-md-10 order-2 order-md-1{/if}">
          <div class="py-1 statement-link">
            {include "bits/statementLink.tpl" statement=$s quotes=false}
          </div>

          <div class="text-end small py-1">
            {if $showEntity}
              {$entity=$s->getEntity()}
              — {include "bits/entityLink.tpl" e=$entity},
            {/if}
            {$s->dateMade|ld}
          </div>
        </div>

        {if $showEntity}
          <div class="col-12 col-md-2 text-center order-1 order-md-2">
            {include "bits/image.tpl"
              obj=$e
              geometry=Config::THUMB_ENTITY_STATEMENT_LIST
              imgClass="rounded-circle img-fluid"}
          </div>
        {/if}

      </div>

      <div class="mask bg-verdict-{$s->verdict} px-3 py-2">
        {* d-inline-block is necessary for ::first-letter to kick in *}
        <span class="capitalize-first-word d-inline-block">{$s->getVerdictLabel()}:</span>
        <span class="text-uppercase">{$s->getVerdictName()}</span>
      </div>
    </div>
  {/foreach}
</div>
