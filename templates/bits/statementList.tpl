{$entityImages=$entityImages|default:true}

{foreach $statements as $s}
  <div class="statement clearfix">
    {$entity=$s->getEntity()}
    {if $entityImages && $entity->imageExtension}
      <img
        src="{$entity->getThumbLink(1)}"
        class="img-thumbnail rounded float-right ml-5">
    {/if}

    <div>
      <div class="mb-n2">
        <a href="{Router::link('statement/view')}/{$s->id}">
          {$s->contents|md}
        </a>
      </div>

      <div class="text-right">
        --
        {strip}
        <a href="{Router::link('entity/view')}/{$entity->id}">
          {$entity->name|escape}
        </a>,
          {/strip}
          {$s->dateMade|ld}
      </div>

      <div class="text-right text-muted">
        {t}added by{/t} <b>{$s->getUser()|escape}</b>
        {$s->createDate|moment}
      </div>
    </div>
  </div>
{/foreach}
