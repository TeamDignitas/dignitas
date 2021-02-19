{* a set of relation-specific fields, to be used on the entity edit page *}
{* mandatory argument: $entityTypeId *}
{$id=$id|default:''}
{$relation=$relation|default:null}

<tr {if $id}id="{$id}" hidden{/if}>
  <td>
    <input type="hidden" name="relIds[]" value="{$relation->id|default:''}">
    {include "bits/icon.tpl" i=drag_indicator class="drag-indicator pt-1"}
  </td>

  <td>
    <select class="form-control form-control-sm" name="relTypes[]">
      {foreach RelationType::loadForEntityType($entityTypeId) as $rt}
        <option
          value="{$rt->id}"
          {if $relation && $relation->relationTypeId == $rt->id}selected{/if}>
          {$rt->name|escape}
        </option>
      {/foreach}
    </select>
  </td>

  <td class="col-entity-id">
    <select name="relEntityIds[]" class="form-control to-entity-id">
      <option value="{$relation->toEntityId|default:''}"></option>
    </select>
  </td>

  <td>
    <div class="input-group">
      <input type="text" class="form-control form-control-sm datepicker">
      <input type="hidden" name="relStartDates[]" value="{$relation->startDate|default:''}">
    </div>
  </td>

  <td>
    <div class="input-group">
      <input type="text" class="form-control form-control-sm datepicker">
      <input type="hidden" name="relEndDates[]" value="{$relation->endDate|default:''}">
    </div>
  </td>

  <td>
    <button
      type="button"
      class="btn btn-sm btn-outline-danger delete-dependant"
      data-toggle="tooltip"
      title="{t}link-delete-relation{/t}">
      {include "bits/icon.tpl" i=delete_forever}
    </button>
  </td>
</tr>
