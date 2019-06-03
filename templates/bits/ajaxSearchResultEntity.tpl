<div>
  {include "bits/image.tpl"
    obj=$entity
    size=Config::THUMB_ENTITY_SEARCH_AUTOCOMPLETE
    imgClass="pic float-left mr-2"}

  {$entity->name|escape}

  <div class="float-right">
    <small class="text-muted">
      {$entity->getTypeName()}
    </small>
  </div>
</div>
