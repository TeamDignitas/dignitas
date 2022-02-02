{* optional argument: $link, a Link object *}
{$rowId=$rowId|default:''}

<tr {if $rowId}id="{$rowId}" hidden{/if}>
  <td>
    <input type="hidden" name="linkIds[]" value="{$link->id|default:''}">
    {include "bits/icon.tpl" i=drag_indicator class="drag-indicator"}
  </td>

  <td>
    <input
      type="text"
      name="linkUrls[]"
      value="{$link->url|default:''|esc}"
      class="form-control">
  </td>

  <td>
    <button
      type="button"
      class="btn btn-outline-danger delete-link"
      data-bs-toggle="tooltip"
      title="{t}link-delete-source{/t}">
      {include "bits/icon.tpl" i=delete_forever}
    </button>
  </td>
</tr>
