{* a set of statement sources, to be used on the statement edit page *}
{$id=$id|default:''}

<tr {if $id}id="{$id}" hidden{/if}>
  <td>
    <input type="hidden" name="ssIds[]" value="{$source->id|default:''}">
    <label class="col-form-label icon icon-move">
    </label>
  </td>

  <td>
    <input
      type="text"
      name="ssUrls[]"
      value="{$source->url|default:''}"
      class="form-control">
  </td>

  <td>
    <button type="button" class="btn btn-danger deleteSourceButton">
      <i class="icon icon-trash"></i>
    </button>
  </td>
</tr>
