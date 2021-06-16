{$cancelLink=$cancelLink|default:false}
{$cloneButton=$cloneButton|default:false}
{$deleteButton=$deleteButton|default:false}
{$deleteButtonConfirm=$deleteButtonConfirm|default:''}
{$deleteButtonDisabled=$deleteButtonDisabled|default:false}
{$deleteButtonTitle=$deleteButtonTitle|default:''}
{$divClass=$divClass|default:''}
{$reopenButton=$reopenButton|default:false}
{$reopenButtonConfirm=$reopenButtonConfirm|default:''}
{$saveButton=$saveButton|default:true}
{$saveButtonText=$saveButtonText|default:"{t}link-save{/t}"}
{$saveDraftButton=$saveDraftButton|default:false}

<div class="mt-4 text-end {$divClass}">

  {if $deleteButton}
    <button
      class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 me-2 mb-2"
      {if $deleteButtonConfirm}data-confirm="{$deleteButtonConfirm}"{/if}
      {if $deleteButtonDisabled}
      disabled
      title="{$deleteButtonTitle}"
      {/if}
      name="deleteButton"
      type="submit">
      {include "bits/icon.tpl" i=delete_forever}
      {t}link-delete{/t}
    </button>
  {/if}

  {if $reopenButton}
    <button
      class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2"
      {if $reopenButtonConfirm}data-confirm="{$reopenButtonConfirm}"{/if}
      name="reopenButton"
      type="submit">
      {t}link-reopen{/t}
    </button>
  {/if}

  {if $cloneButton}
    <button
      class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2"
      name="cloneButton"
      type="submit">
      {include "bits/icon.tpl" i=content_copy}
      {t}link-clone{/t}
    </button>
  {/if}

  {if $cancelLink}
    <a
      class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2"
      href="{$cancelLink}">
      {include "bits/icon.tpl" i=cancel}
      {t}link-cancel{/t}
    </a>
  {/if}

  {if $saveDraftButton}
    <button
      name="saveDraftButton"
      type="submit"
      class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2"
      data-bs-toggle="tooltip"
      title="{t}tooltip-save-draft{/t}">
      {include "bits/icon.tpl" i=insert_drive_file}
      {t}link-save-draft{/t}
    </button>
  {/if}

  {if $saveButton}
    <button
      class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2"
      name="saveButton"
      type="submit">
      {include "bits/icon.tpl" i=save}
      {$saveButtonText}
    </button>
  {/if}

</div>
