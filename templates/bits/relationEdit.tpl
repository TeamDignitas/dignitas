{* a set of relation-specific fields, to be used on the entity edit page *}
{$id=$id|default:''}
{$relation=$relation|default:null}

<tr {if $id}id="{$id}" hidden{/if}>
  <td>
    <input type="hidden" name="relIds[]" value="{$relation->id|default:''}">
    <label class="col-form-label icon icon-move">
    </label>
  </td>

  <td>
    <select class="form-control" name="relTypes[]">
      {foreach Relation::TYPES as $t}
        <option value="{$t}" {if $relation && $relation->type == $t}selected{/if}>
          {Relation::typeName($t)}
        </option>
      {/foreach}
    </select>
  </td>

  <td>
    <select name="relEntityIds[]" class="form-control toEntityId">
      <option value="{$relation->toEntityId|default:''}"></option>
    </select>
  </td>

  <td>
    <input
      type="date"
      name="relStartDates[]"
      value="{$relation->startDate|default:''}"
      class="form-control">
  </td>

  <td>
    <input
      type="date"
      name="relEndDates[]"
      value="{$relation->endDate|default:''}"
      class="form-control">
  </td>

  <td>
    <button type="button" class="btn btn-danger deleteRelationButton">
      <i class="icon icon-trash"></i>
    </button>
  </td>
</tr>
