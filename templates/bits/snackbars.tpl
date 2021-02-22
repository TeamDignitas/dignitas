<div id="snackbars" aria-live="polite" aria-atomic="true">
  {foreach $snackbars as $msg}
    {include "bits/snackbar.tpl"}
  {/foreach}
</div>

{* an extra stub that can be cloned for snackbars generated in JS *}
<div id="snackbar-stub" class="d-none">
  {include "bits/snackbar.tpl"}
</div>
