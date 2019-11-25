{* a set of statement/relation sources, to be used on edit pages *}
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
      value="{$source->url|escape|default:''}"
      class="form-control">
  </td>

  <td>
    <button type="button" class="btn btn-danger deleteSourceButton">
      <i class="icon icon-trash"></i>
    </button>
  </td>
</tr>
