{$e=$statement->getEntity()}
<div class="row">
  <div class="col-3">
    {include "bits/image.tpl"
      obj=$e
      geometry=Config::THUMB_ENTITY_LARGE
      imgClass="pic rounded-circle img-fluid no-outline"}
  </div>
  <div class="col-9">
    <div class="bubble mr-3 mb-2 p-3">
      <div class="bubble-title ml-1">
        {include "bits/statementLink.tpl"
          class="stretched-link"
          quotes=false}
      </div>
    </div>
    <div class="bubble-author">
      — {include "bits/entityLink.tpl"}, {$statement->dateMade|ld}
    </div>
  </div>
</div>
