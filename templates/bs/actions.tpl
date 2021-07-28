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
{* $saveButtonClass will be ignored if $saveDraftButton is set *}
{$saveButtonClass=$saveButtonClass|default:'btn-primary'}
{$saveButtonConfirm=$saveButtonConfirm|default:false} {* boolean *}
{$saveButtonText=$saveButtonText|default:"{t}link-save{/t}"}
{$saveDraftButton=$saveDraftButton|default:false}

{$colDef="col-12 col-sm-6 col-md-3 col-lg-2"}
{$btnDef="btn btn-sm w-100"}

<div class="row g-2 justify-content-end mt-4 {$divClass}">

  {if $deleteButton}
    <div class="{$colDef}">
      <button
        class="{$btnDef} btn-outline-danger"
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
    </div>
  {/if}

  {if $reopenButton}
    <div class="{$colDef}">
      <button
        class="{$btnDef} btn-outline-secondary"
        {if $reopenButtonConfirm}data-confirm="{$reopenButtonConfirm}"{/if}
        name="reopenButton"
        type="submit">
      {t}link-reopen{/t}
    </button>
  {/if}

  {if $cloneButton}
    <div class="{$colDef}">
      <button
        class="{$btnDef} btn-outline-secondary"
        name="cloneButton"
        type="submit">
        {include "bits/icon.tpl" i=content_copy}
        {t}link-clone{/t}
      </button>
    </div>
  {/if}

  {if $cancelLink}
    <div class="{$colDef}">
      <a
        class="{$btnDef} btn-outline-secondary"
        href="{$cancelLink}">
        {include "bits/icon.tpl" i=cancel}
        {t}link-cancel{/t}
      </a>
    </div>
  {/if}

  {if $saveDraftButton}
    <div class="{$colDef}">
      <button
        class="{$btnDef} btn-primary"
        data-bs-toggle="tooltip"
        name="saveDraftButton"
        title="{t}tooltip-save-draft{/t}"
        type="submit">
        {include "bits/icon.tpl" i=insert_drive_file}
        {t}link-save-draft{/t}
      </button>
    </div>
  {/if}

  {if $saveButton}
    <div class="{$colDef}">
      <button
        class="{$btnDef} {if $saveDraftButton}btn-outline-secondary{else}{$saveButtonClass}{/if}"
        {if $saveButtonConfirm}data-confirm="{t}info-confirm-publish{/t}"{/if}
        name="saveButton"
        type="submit">
        {include "bits/icon.tpl" i=save}
        {$saveButtonText}
      </button>
    </div>
  {/if}

</div>
