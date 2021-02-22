{$msg=$msg|default:''} {* can be empty if supplied by the backend at a later time *}
{$cssClass=$cssClass|default:''}
<div
  {if isset($id)}
  id="{$id}"
  {/if}
  class="toast {$cssClass} bg-darkish text-light"
  role="alert"
  data-delay="5000"
  aria-live="assertive"
  aria-atomic="true">

  <div class="toast-body">
    {$msg}
  </div>
</div>
