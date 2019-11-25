{* optional argument: $item, a UrlTrait object *}
{$rowId=$rowId|default:''}

<tr {if $rowId}id="{$rowId}" hidden{/if}>
  <td>
    <input type="hidden" name="urlIds[]" value="{$item->id|default:''}">
    <label class="col-form-label icon icon-move">
    </label>
  </td>

  <td>
    <input
      type="text"
      name="urls[]"
      value="{$item->url|escape|default:''}"
      class="form-control">
  </td>

  <td>
    <button type="button" class="btn btn-danger deleteUrlButton">
      <i class="icon icon-trash"></i>
    </button>
  </td>
</tr>
