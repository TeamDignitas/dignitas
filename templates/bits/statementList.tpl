{$entityImages=$entityImages|default:true}
{$addedBy=$addedBy|default:true}
{$addStatement=$addStatement|default:false}
{$addStatementEntityId=$addStatementEntityId|default:null}

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

  {if $addStatement}
    <div class="statement card border-secondary mr-3 mb-3 py-4 px-4">
      <div class="card-body small">
        <a
          href="{Router::link('statement/edit')}?entityId={$addStatementEntityId}"
          class="btn btn-primary">
          {t}link-add-statement{/t}
        </a>
      </div>

    </div>
  {/if}

</div>
