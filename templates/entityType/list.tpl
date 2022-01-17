{extends "layout.tpl"}

{block "title"}{cap}{t}title-entity-types{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-entity-types{/t}{/cap}</h1>

    <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('entityType/edit')}">
      {include "bits/icon.tpl" i=add_circle}
      {t}link-add-entity-type{/t}
    </a>

    {if count($entityTypes)}
      <div class="gtable container mt-5">
        <div class="row gtable-header">
          <div class="col-12 col-sm-5">{t}label-name{/t}</div>
          <div class="col-3 col-sm-2 text-center">{t}label-loyalty-source{/t}</div>
          <div class="col-3 col-sm-2 text-center">{t}label-loyalty-sink{/t}</div>
          <div class="col-3 col-sm-2 text-center">{t}label-has-color{/t}</div>
          <div class="col-3 col-sm-1 text-center">{t}actions{/t}</div>
        </div>

        {foreach $entityTypes as $et}
          <div  class="row gtable-row">
            <div class="col-12 col-sm-5">
              {$et->name|escape}
              {if $et->isDefault}
                ({t}label-is-default{/t})
              {/if}
            </div>
            <div class="col-3 col-sm-2 text-center">
              {if $et->loyaltySource}
                {include "bits/icon.tpl" i=done}
              {/if}
            </div>
            <div class="col-3 col-sm-2 text-center">
              {if $et->loyaltySink}
                {include "bits/icon.tpl" i=done}
              {/if}
            </div>
            <div class="col-3 col-sm-2 text-center">
              {if $et->hasColor}
                {include "bits/icon.tpl" i=done}
              {/if}
            </div>
            <div class="col-3 col-sm-1 text-center">
              <a
                href="{$et->getEditUrl()}"
                class="btn"
                title="{t}link-edit{/t}">
                {include "bits/icon.tpl" i=mode_edit}
              </a>
            </div>
          </div>
        {/foreach}
      </div>
    {/if}
  </div>
{/block}
