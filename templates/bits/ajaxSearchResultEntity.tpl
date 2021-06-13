<div>
  {include "bits/image.tpl"
    obj=$entity
    geometry=Config::THUMB_ENTITY_AUTOCOMPLETE
    imgClass="pic me-2 align-middle"}

  {$entity->name|escape}

  {if count($aliases)}
    <small class="text-muted">
      {strip}
      (
      <ul class="list-inline list-inline-bullet d-inline">
        {foreach $aliases as $a}
          <li class="list-inline-item">{$a->name}</li>
        {/foreach}
      </ul>
      )
      {/strip}
    </small>
  {/if}

  <div class="float-end">
    <small class="text-muted">
      {$entity->getEntityType()->name|escape}
    </small>
  </div>
</div>
