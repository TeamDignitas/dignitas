{$entityImages=$entityImages|default:true}

{foreach $statements as $s}
  <div class="statement clearfix">
    {$entity=$s->getEntity()}
    {include "bits/image.tpl"
      obj=$entity
      condition=$entityImages && $entity->imageExtension
      geometry=Config::THUMB_ENTITY_LARGE
      imgClass="pic float-right ml-5"}

    <div>
      <div>
        <a href="{Router::link('statement/view')}/{$s->id}">
          {$s->summary|escape}
        </a>
      </div>

      <div class="text-right">
        — {include "bits/entityLink.tpl" e=$entity},
        {$s->dateMade|ld}
      </div>

      <div class="text-right text-muted small">
        {t}added by{/t}
        {include 'bits/userLink.tpl' u=$s->getUser()}
        {$s->createDate|moment}
      </div>
    </div>
  </div>
{/foreach}
