{$msg=$msg|default:''} {* can be empty if supplied by the backend at a later time *}
{$status=$status|default:'success'} {* a bootstrap status color *}
{$cssClass=$cssClass|default:''}
<div
  {if isset($id)}
  id="{$id}"
  {/if}
  class="toast {$cssClass}"
  role="alert"
  data-delay="2000"
  aria-live="assertive"
  aria-atomic="true">

  <div class="toast-header">
    <span class="toast-color rounded mr-auto bg-{$status}">&nbsp;</span>
    <button type="button" class="mt-2 close" data-dismiss="toast" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="toast-body">
    {$msg}
  </div>
</div>
