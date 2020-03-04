{$entityImages=$entityImages|default:true}
{$addedBy=$addedBy|default:true}

{foreach $statements as $s}
  <div class="statement col-md-12">
    {$entity=$s->getEntity()}
    {include "bits/image.tpl"
      obj=$entity
      condition=$entityImages && $entity->fileExtension
      geometry=Config::THUMB_ENTITY_LARGE
      imgClass="pic float-right ml-5"}

    <div>
      <div>
        {include "bits/statementLink.tpl" statement=$s}
      </div>

      <div class="text-right">
        — {include "bits/entityLink.tpl" e=$entity},
        {$s->dateMade|ld}
      </div>

      {if $addedBy}
        <div class="text-right text-muted small">
          {t}statement-added-by{/t}
          {include 'bits/userLink.tpl' u=$s->getUser()}
          {include 'bits/moment.tpl' t=$s->createDate}
        </div>
      {/if}
    </div>
  </div>
{/foreach}
