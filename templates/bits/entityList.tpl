{foreach $entities as $e}
  <div class="clearfix">
    {include "bits/image.tpl"
      obj=$e
      geometry=Config::THUMB_ENTITY_SMALL
      imgClass="pic float-right"}

    {include "bits/entityLink.tpl" e=$e}
    <div>{$e->getEntityType()->name|escape}</div>
  </div>
  <hr>
{/foreach}
