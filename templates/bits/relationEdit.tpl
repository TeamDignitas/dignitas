{* a set of relation-specific fields, to be used on the entity edit page *}
{$id=$id|default:''}
{$relation=$relation|default:null}

<div {if $id}id="{$id}"{/if} class="form-row relationWrapper my-1">
  <input type="hidden" name="relIds[]" value="{$relation->id|default:''}">
  <div class="col-auto">
    <label class="col-form-label icon icon-move">
    </label>
  </div>
  <div class="col">
    <select class="form-control" name="relTypes[]">
      {foreach Relation::TYPES as $t}
        <option value="{$t}" {if $relation && $relation->type == $t}selected{/if}>
          {Relation::typeName($t)}
        </option>
      {/foreach}
    </select>
  </div>

  <div class="col-4">
    <select name="relEntityIds[]" class="form-control toEntityId">
      <option value="{$relation->toEntityId|default:''}"></option>
    </select>
  </div>

  <div class="col">
    <input
      type="text"
      name="relStartDates[]"
      value="{$relation->startDate|default:''}"
      class="form-control">
  </div>

  <div class="col">
    <input
      type="text"
      name="relEndDates[]"
      value="{$relation->endDate|default:''}"
      class="form-control">
  </div>

  <div class="col">
    <button type="button" class="btn btn-danger deleteRelationButton">
      <i class="icon icon-trash"></i>
    </button>
  </div>
</div>
