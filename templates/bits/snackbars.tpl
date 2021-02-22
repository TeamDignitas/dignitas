<div id="snackbars" aria-live="polite" aria-atomic="true">
  {foreach $snackbars as $msg}
    {include "bits/toast.tpl"}
  {/foreach}
</div>
