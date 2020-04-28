{* optional argument: $link, a Link object *}
{$rowId=$rowId|default:''}

<tr {if $rowId}id="{$rowId}" hidden{/if}>
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

  <td class="narrow-column">
    <button type="button" class="btn btn-outline-danger delete-link">
      <i class="icon icon-trash"></i>
    </button>
  </td>
</tr>
