{* a set of alias-specific fields, to be used on the entity edit page *}
{$id=$id|default:''}
{$alias=$alias|default:null}

<tr {if $id}id="{$id}" hidden{/if}>
  <td>
    <input type="hidden" name="aliasIds[]" value="{$alias->id|default:''}">
    {include "bits/icon.tpl" i=drag_indicator class="drag-indicator"}
  </td>

  <td>
    <input
      type="text"
      name="aliasNames[]"
      value="{$alias->name|escape|default:''}"
      class="form-control">
  </td>

  <td>
    <button
      type="button"
      class="btn btn-outline-danger delete-dependant"
      data-bs-toggle="tooltip"
      title="{t}link-delete-alias{/t}">
      {include "bits/icon.tpl" i=delete_forever}
    </button>
  </td>
</tr>
