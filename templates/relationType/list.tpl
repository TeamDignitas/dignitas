{extends "layout.tpl"}

{block "title"}{cap}{t}title-relation-types{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-relation-types{/t}{/cap}</h1>

    {if $numEntityTypes}
      <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('relationType/edit')}">
        {include "bits/icon.tpl" i=add_circle}
        {t}link-add-relation-type{/t}
      </a>
    {else}
      {t 1=Router::link('entityType/list')}
      info-add-entity-types-before-relation-types-%1
      {/t}
    {/if}

    {if count($relationTypes)}
      <div class="gtable container">
        <div class="row gtable-header">
          <div class="col-12 col-md-5">{t}label-name{/t}</div>
          <div class="col-4 col-md-2">{t}label-from-entity-type{/t}</div>
          <div class="col-4 col-md-3">{t}label-to-entity-type{/t}</div>
          <div class="col-2 col-md-1 text-center">{t}label-weight{/t}</div>
          <div class="col-2 col-md-1 text-center">{t}actions{/t}</div>
        </div>

        {foreach $relationTypes as $rt}
          <div class="row gtable-row">
            <div class="col-12 col-md-5">{$rt->name|escape}</div>
            <div class="col-4 col-md-2">{$rt->getFromEntityType()->name|escape}</div>
            <div class="col-4 col-md-3">{$rt->getToEntityType()->name|escape}</div>
            <div class="col-2 col-md-1 text-center">{$rt->weight}</div>
            <div class="col-2 col-md-1 text-center">
              <a
                href="{$rt->getEditUrl()}"
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
