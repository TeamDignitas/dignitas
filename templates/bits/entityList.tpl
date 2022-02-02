<div class="row g-2 mt-4">
  {foreach $entities as $e}
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100 p-2 text-center">
        {include "bits/image.tpl"
          obj=$e
          geometry=Config::THUMB_ENTITY_SMALL
          imgClass="rounded-circle img-fluid"}

        <span class="ms-2 mt-2">{include "bits/entityLink.tpl" e=$e}</span>
        <div class="ms-2 text-muted small">{$e->getEntityType()->name|esc}</div>
      </div>
    </div>
  {/foreach}
</div>
