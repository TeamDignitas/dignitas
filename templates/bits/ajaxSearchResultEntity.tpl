<div>
  {include "bits/image.tpl"
    obj=$entity
    size=Config::THUMB_ENTITY_SEARCH_AUTOCOMPLETE
    imgClass="pic mr-2 align-middle"}

  {$entity->name|escape}

  {if count($aliases)}
    <small class="text-muted">
      {strip}
      (
      <ul class="list-inline aliasListAjax">
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
