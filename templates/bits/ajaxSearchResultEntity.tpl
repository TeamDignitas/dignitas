<div>
  {include "bits/image.tpl"
    obj=$entity
    geometry=Config::THUMB_ENTITY_AUTOCOMPLETE
    imgClass="pic mr-2 align-middle"}

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

  <div class="float-right">
    <small class="text-muted">
      {$entity->getTypeName()}
    </small>
  </div>
</div>
