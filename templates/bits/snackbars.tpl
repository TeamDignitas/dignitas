{if $snackbars}
  <div id="snackbars" class="w-50 mx-auto">
    {foreach $snackbars as $s}
      <div class="alert alert-{$s.type} alert-dismissible fade show" role="alert">
        {$s.text}
        <button
          type="button"
          class="close"
          data-dismiss="alert"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    {/foreach}
  </div>
{/if}
