{extends "layout.tpl"}

{block "title"}{cap}{t}title-entity-types{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-entity-types{/t}{/cap}</h1>

    <a class="btn btn-sm btn-primary col-sm-12 col-md-3" href="{Router::link('entityType/edit')}">
      {include "bits/icon.tpl" i=add_circle}
      {t}link-add-entity-type{/t}
    </a>

    {if count($entityTypes)}
      <table class="table table-hover mt-5 mb-4">
        <thead>
          <tr class="small">
            <th class="border-0">{t}label-name{/t}</th>
            <th class="border-0 text-center">{t}label-loyalty-source{/t}</th>
            <th class="border-0 text-center">{t}label-loyalty-sink{/t}</th>
            <th class="border-0 text-center">{t}label-has-color{/t}</th>
            <th class="border-0">{t}actions{/t}</th>
          </tr>
        </thead>
        <tbody>
          {foreach $entityTypes as $et}
            <tr class="small">
              <td class="align-middle">
                {$et->name|escape}
                {if $et->isDefault}
                  ({t}label-is-default{/t})
                {/if}
              </td>
              <td class="align-middle text-center">
                {if $et->loyaltySource}
                  {include "bits/icon.tpl" i=done}
                {/if}
              </td>
              <td class="align-middle text-center">
                {if $et->loyaltySink}
                  {include "bits/icon.tpl" i=done}
                {/if}
              </td>
              <td class="align-middle text-center">
                {if $et->hasColor}
                  {include "bits/icon.tpl" i=done}
                {/if}
              </td>
              <td>
                <a
                  href="{$et->getEditUrl()}"
                  class="btn"
                  title="{t}link-edit{/t}">
                  {include "bits/icon.tpl" i=mode_edit}
                </a>
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    {/if}
  </div>
{/block}
