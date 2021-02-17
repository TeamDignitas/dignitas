{extends "layout.tpl"}

{block "title"}{cap}{t}title-relation-types{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-relation-types{/t}{/cap}</h1>

    {if $numEntityTypes}
      <a class="btn btn-sm btn-primary col-sm-12 col-md-3" href="{Router::link('relationType/edit')}">
        {include "bits/icon.tpl" i=add_circle}
        {t}link-add-relation-type{/t}
      </a>
    {else}
      {t 1=Router::link('entityType/list')}
      info-add-entity-types-before-relation-types-%1
      {/t}
    {/if}

    {if count($relationTypes)}
      <form method="post">
        <table class="table table-hover mt-5 mb-4 sortable">
          <thead>
            <tr class="small">
              <th class="border-0">{t}label-order{/t}</th>
              <th class="border-0">{t}label-name{/t}</th>
              <th class="border-0">{t}label-from-entity-type{/t}</th>
              <th class="border-0">{t}label-to-entity-type{/t}</th>
              <th class="border-0">{t}label-weight{/t}</th>
              <th class="border-0">{t}actions{/t}</th>
            </tr>
          </thead>
          <tbody>
            {foreach $relationTypes as $rt}
              <tr class="small">
                <td class="align-middle">
                  <input type="hidden" name="ids[]" value="{$rt->id}">
                  {include "bits/icon.tpl" i=drag_indicator class="drag-indicator"}
                </td>
                <td class="align-middle">{$rt->name|escape}</td>
                <td class="align-middle">{$rt->getFromEntityType()->name|escape}</td>
                <td class="align-middle">{$rt->getToEntityType()->name|escape}</td>
                <td class="align-middle">{$rt->weight}</td>
                <td>
                  <a
                    href="{$rt->getEditUrl()}"
                    class="btn"
                    title="{t}link-edit{/t}">
                    {include "bits/icon.tpl" i=mode_edit}
                  </a>
                </td>
              </tr>
            {/foreach}
          </tbody>
        </table>

        <div class="mt-4 text-right">
          <a
            href="{Router::link('relationType/list')}"
            class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
            {include "bits/icon.tpl" i=cancel}
            {t}link-cancel{/t}
          </a>

          <button
            type="submit"
            class="btn btn-sm btn-outline-primary col-sm-4 col-lg-2 mb-2"
            name="saveButton">
            {include "bits/icon.tpl" i=save}
            {t}link-save-order{/t}
          </button>
        </div>
      </form>
    {/if}
  </div>
{/block}
