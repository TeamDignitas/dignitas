{$e=$statement->getEntity()}
<div class="bubble">
  <div class="bubble-body px-4 pt-4 pb-1">
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
  </div>

  <div class="bubble-author px-4 py-3">
    — {include "bits/entityLink.tpl"}, {$statement->dateMade|ld}
  </div>
</div>
