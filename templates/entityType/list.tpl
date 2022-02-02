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
      <div class="table-responsive">
        <table class="table dtable mt-5 text-nowrap">

          <thead>
            <tr>
              <th>{t}label-name{/t}</th>
              <th class="text-center">{t}label-loyalty-source{/t}</th>
              <th class="text-center">{t}label-loyalty-sink{/t}</th>
              <th class="text-center">{t}label-has-color{/t}</th>
              <th class="text-center">{t}actions{/t}</th>
            </tr>
          </thead>

          <tbody>
            {foreach $entityTypes as $et}
              <tr>
                <td>
                  {$et->name|esc}
                  {if $et->isDefault}
                    ({t}label-is-default{/t})
                  {/if}
                </td>
                <td class="text-center">
                  {if $et->loyaltySource}
                    {include "bits/icon.tpl" i=done}
                  {/if}
                </td>
                <td class="text-center">
                  {if $et->loyaltySink}
                    {include "bits/icon.tpl" i=done}
                  {/if}
                </td>
                <td class="text-center">
                  {if $et->hasColor}
                    {include "bits/icon.tpl" i=done}
                  {/if}
                </td>
                <td class="text-center">
                  <a
                    href="{$et->getEditUrl()}"
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
