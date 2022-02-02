{extends "layout.tpl"}

{block "title"}{t}title-edit-relation-type{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{t}title-edit-relation-type{/t}</h1>

    <form method="post">

      {field inputId="field-name" label="{t}label-name{/t}"}
        <input type="text"
          class="form-control {if isset($errors.name)}is-invalid{/if}"
          id="field-name"
          name="name"
          value="{$rt->name|esc}"
          placeholder="{t}info-relation-type-name{/t}">
        {include "bs/feedback.tpl" errors=$errors.name|default:null}
      {/field}

      {field
        inputId="field-from-entity-type-id"
        label="{t}label-from-entity-type{/t}"}
        <select
          class="form-select"
          id="field-from-entity-type-id"
          name="fromEntityTypeId">
          {foreach $entityTypes as $et}
            <option
              value="{$et->id}"
              {if $rt->fromEntityTypeId == $et->id}selected{/if}>
              {$et->name}
            </option>
          {/foreach}
        </select>
      {/field}

      {field
        inputId="field-to-entity-type-id"
        label="{t}label-to-entity-type{/t}"}
        <select
          class="form-select"
          id="field-to-entity-type-id"
          name="toEntityTypeId">
          {foreach $entityTypes as $et}
            <option
              value="{$et->id}"
              {if $rt->toEntityTypeId == $et->id}selected{/if}>
              {$et->name}
            </option>
          {/foreach}
        </select>
      {/field}

      {field inputId="field-phrase" label="{t}label-phrase{/t}"}
        <select
          class="form-select"
          id="field-phrase"
          name="phrase">
          {foreach RelationType::getPhrases() as $phrase}
            <option
              value="{$phrase}"
              {if $rt->phrase == $phrase}selected{/if}>
              {RelationType::phraseName($phrase)}
            </option>
          {/foreach}
        </select>
        <div class="form-text">
          {t}info-relation-type-phrase{/t}
        </div>
      {/field}

      {field inputId="field-weight" label="{t}label-weight{/t}"}
        <input type="number"
          class="form-control {if isset($errors.weight)}is-invalid{/if}"
          id="field-weight"
          name="weight"
          value="{$rt->weight}"
          min="0.0"
          max="1.0"
          step="0.001"
          placeholder="{t}info-relation-type-weight{/t}">
        {include "bs/feedback.tpl" errors=$errors.weight|default:null}
      {/field}

      {include 'bs/checkbox.tpl'
        cbErrors=$errors.symmetric|default:null
        checked=$rt->symmetric
        divClass='mb-3'
        help="{t}info-symmetric{/t}"
        label="{t}label-symmetric{/t}"
        name='symmetric'}

      {include 'bs/checkbox.tpl'
        checked=$rt->membership
        help="{t}info-relation-type-membership{/t}"
        label="{t}label-relation-type-membership{/t}"
        name='membership'}

      {include "bs/actions.tpl"
        cancelLink=Router::link('relationType/list')
        cloneButton=$rt->id
        deleteButton=$rt->canDelete()
        deleteButtonConfirm="{t}info-confirm-delete-relation-type{/t}"}

    </form>
  </div>
{/block}
