{extends "layout.tpl"}

{block "title"}{cap}{t}title-relation-types{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-relation-types{/t}{/cap}</h1>

    {if $numEntityTypes}
      <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('relationType/edit')}">
        {t}link-add-relation-type{/t}
      </a>
    {else}
      {t 1=Router::link('entityType/list')}
      info-add-entity-types-before-relation-types-%1
      {/t}
    {/if}

    {if count($relationTypes)}
      <div class="table-responsive">
        <table class="table dtable mt-5">

          <thead>
            <tr>
              <th>{t}label-name{/t}</th>
              <th>{t}label-from-entity-type{/t}</th>
              <th>{t}label-to-entity-type{/t}</th>
              <th class="text-center">{t}label-weight{/t}</th>
              <th class="text-center">{t}actions{/t}</th>
            </tr>
          </thead>

          <tbody>
            {foreach $relationTypes as $rt}
              <tr>
                <td>{$rt->name|esc}</td>
                <td>{$rt->getFromEntityType()->name|esc}</td>
                <td>{$rt->getToEntityType()->name|esc}</td>
                <td class="text-center">{$rt->weight}</td>
                <td class="text-center">
                  <a
                    href="{$rt->getEditUrl()}"
                    class="btn btn-sm"
                    title="{t}link-edit{/t}">
                    {include "bits/icon.tpl" i=mode_edit}
                  </a>
                </td>
              </tr>
            {/foreach}
          </tbody>

        </table>
      </div>
    {/if}
  </div>
{/block}
