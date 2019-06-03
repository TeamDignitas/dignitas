<div>
  {include "bits/image.tpl"
    obj=$entity
    size=Config::THUMB_ENTITY_SEARCH_AUTOCOMPLETE
    imgClass="pic mr-2 align-middle"}

  {$entity->name|escape}

  <div class="float-right">
    <small class="text-muted">
      {$entity->getTypeName()}
    </small>
  </div>
</div>
