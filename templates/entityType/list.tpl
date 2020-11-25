{extends "layout.tpl"}

{block "title"}{cap}{t}title-entity-types{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-entity-types{/t}{/cap}</h1>

    <a class="btn btn-sm btn-primary" href="{Router::link('entityType/edit')}">
      <i class="icon icon-plus"></i>
      {t}link-add-entity-type{/t}
    </a>

    {if count($entityTypes)}
      <table class="table table-hover mt-5 mb-4">
        <thead>
          <tr class="small">
            <th class="border-0">{t}label-name{/t}</th>
            <th class="border-0">{t}label-loyalty-source{/t}</th>
            <th class="border-0">{t}label-loyalty-sink{/t}</th>
            <th class="border-0">{t}label-has-color{/t}</th>
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
              <td class="align-middle">
                {if $et->loyaltySource}
                  <i class="icon icon-ok"></i>
                {/if}
              </td>
              <td class="align-middle">
                {if $et->loyaltySink}
                  <i class="icon icon-ok"></i>
                {/if}
              </td>
              <td class="align-middle">
                {if $et->hasColor}
                  <i class="icon icon-ok"></i>
                {/if}
              </td>
              <td>
                <a
                  href="{$et->getEditUrl()}"
                  class="btn"
                  title="{t}link-edit{/t}">
                  <i class="icon icon-pencil"></i>
                </a>
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    {/if}
  </div>
{/block}
