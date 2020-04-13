{extends "layout.tpl"}

{block "title"}{cap}{t}title-relation-types{/t}{/cap}{/block}

{block "content"}

  <h2 class="mb-4">{cap}{t}title-relation-types{/t}{/cap}</h2>

  {if $numEntityTypes}
    <a class="btn btn-sm btn-outline-primary" href="{Router::link('relationType/edit')}">
      <i class="icon icon-plus"></i>
      {t}link-add-relation-type{/t}
    </a>
  {else}
    {t 1=Router::link('entityType/list')}
    info-add-entity-types-before-relation-types-%1
    {/t}
  {/if}

  {if count($relationTypes)}
    <form method="post">
      <table class="table table-sm table-hover mt-5 mb-4 sortable">
        <thead>
          <tr>
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
            <tr>
              <td class="align-middle">
                <input type="hidden" name="ids[]" value="{$rt->id}">
                <label class="icon icon-move"></label>
              </td>
              <td class="align-middle">{$rt->name|escape}</td>
              <td class="align-middle">{$rt->getFromEntityType()->name|escape}</td>
              <td class="align-middle">{$rt->getToEntityType()->name|escape}</td>
              <td class="align-middle">{$rt->weight}</td>
              <td>
                <a href="{Router::getEditLink($rt)}" class="btn btn-sm btn-outline-secondary">
                  {t}link-edit{/t}
                </a>
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>

      <div>
        <button
          type="submit"
          class="btn btn-sm btn-outline-primary"
          name="saveButton">
          <i class="icon icon-floppy"></i>
          {t}link-save-order{/t}
        </button>
        <a
          href="{Router::link('relationType/list')}"
          class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>
      </div>
    </form>
  {/if}

{/block}
