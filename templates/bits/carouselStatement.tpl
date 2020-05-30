{$e=$statement->getEntity()}
<div class="bubble p-4">
  <div class="text-center mb-3">
    {include "bits/image.tpl"
      obj=$e
      geometry=Config::THUMB_ENTITY_CAROUSEL
      imgClass="pic rounded-circle img-fluid no-outline"}
  </div>

  <div class="bubble-title mb-3">
    {include "bits/statementLink.tpl"
      class="stretched-link"
      quotes=false}
  </div>

  <div class="bubble-author">
    — {include "bits/entityLink.tpl"}, {$statement->dateMade|ld}
  </div>
</div>
