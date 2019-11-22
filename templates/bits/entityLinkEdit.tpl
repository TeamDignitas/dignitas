{* a set of entity links, to be used on the entity edit page *}
{$id=$id|default:''}

<tr {if $id}id="{$id}" hidden{/if}>
  <td>
    <input type="hidden" name="linkIds[]" value="{$link->id|default:''}">
    <label class="col-form-label icon icon-move">
    </label>
  </td>

  <td>
    <input
      type="text"
      name="linkUrls[]"
      value="{$link->url|escape|default:''}"
      class="form-control">
  </td>

  <td>
    <button type="button" class="btn btn-danger deleteDependantButton">
      <i class="icon icon-trash"></i>
    </button>
  </td>
</tr>
