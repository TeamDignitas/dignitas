{foreach $entities as $e}
  <div class="card col-sm-11 col-md-3 m-2 p-3 text-center">
    {include "bits/image.tpl"
      obj=$e
      geometry=Config::THUMB_ENTITY_SMALL
      imgClass="pic rounded-circle img-fluid no-outline"}

    <span class="ms-2 mt-2">{include "bits/entityLink.tpl" e=$e}</span>
    <div class="ms-2 text-muted small">{$e->getEntityType()->name|escape}</div>
  </div>
{/foreach}
