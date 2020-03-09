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

  <td class="col-entity-id">
    <select name="relEntityIds[]" class="form-control to-entity-id">
      <option value="{$relation->toEntityId|default:''}"></option>
    </select>
  </td>

  <td>
    {include "bits/dateFields.tpl"
      namePrefix="relStartDates"
      nameSuffix="[]"
      date=$relation->startDate|default:null}
  </td>

  <td>
    {include "bits/dateFields.tpl"
      namePrefix="relEndDates"
      nameSuffix="[]"
      date=$relation->endDate|default:null}
  </td>

  <td>
    <button type="button" class="btn btn-danger delete-dependant">
      <i class="icon icon-trash"></i>
    </button>
  </td>
</tr>
