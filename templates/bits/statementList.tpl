{$showEntity=$showEntity|default:true}
<div class="mt-4">
  {foreach $statements as $i => $s}
    {$e=$s->getEntity()}
    <div class="statement card verdict-{$s->verdict} mr-3 mb-3">

      <div class="card-body row">

        <div class="col-12 col-sm-12 col-md-10 col-lg-10 order-2 order-sm-2 order-md-1 order-lg-1">
          <div class="py-1 statement-link">
            {include "bits/statementLink.tpl" statement=$s quotes=false}
          </div>

          <div class="text-right small py-1">
            {if $showEntity}
              {$entity=$s->getEntity()}
              — {include "bits/entityLink.tpl" e=$entity},
            {/if}
            {$s->dateMade|ld}
          </div>
        </div>

        {if $showEntity}
          <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center order-1 order-sm-1 order-md-2 order-lg-2">
            {include "bits/image.tpl"
              obj=$e
              geometry=Config::THUMB_ENTITY_STATEMENT_LIST
              imgClass="pic rounded-circle img-fluid no-outline"}
          </div>
        {/if}

      </div>

      <div class="mask bg-verdict-{$s->verdict}">
        <span class="text-capitalize">{t}label-verdict{/t}:</span>
        {$s->getVerdictName()}
      </div>
    </div>
  {/foreach}
</div>
