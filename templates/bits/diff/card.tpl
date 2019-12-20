{if $old != $new}
  <div class="card diffCard">
    <div class="card-header">
      {$title}
    </div>
    <div class="card-body">
      {include "bits/diff/text.tpl" from=$old to=$new}
    </div>
  </div>
{/if}
