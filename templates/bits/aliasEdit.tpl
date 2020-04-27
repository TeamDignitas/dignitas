{* a set of alias-specific fields, to be used on the entity edit page *}
{$id=$id|default:''}
{$alias=$alias|default:null}

<tr {if $id}id="{$id}" hidden{/if}>
  <td>
    <input type="hidden" name="aliasIds[]" value="{$alias->id|default:''}">
    <label class="col-form-label icon icon-move">
    </label>
  </td>

  <td>
    <input
      type="text"
      name="aliasNames[]"
      value="{$alias->name|escape|default:''}"
      class="form-control">
  </td>

  <td>
    <button type="button" class="btn btn-outline-danger delete-dependant">
      <i class="icon icon-trash"></i>
    </button>
  </td>
</tr>
