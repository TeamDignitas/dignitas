{$entityImages=$entityImages|default:true}
{$addedBy=$addedBy|default:true}

<div class="statement-list card-columns">
  {foreach $statements as $s}
    <div class="statement card border-secondary mr-3 mb-3 py-4 px-4">
      {$entity=$s->getEntity()}
      {include "bits/image.tpl"
        obj=$entity
        condition=$entityImages && $entity->fileExtension
        geometry=Config::THUMB_ENTITY_LARGE
        imgClass="pic float-right ml-5"}

      <div class="">
        <div class="card-title font-italic">
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
