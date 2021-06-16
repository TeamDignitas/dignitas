{* A card on the dashboard. Arguments: $icon, $link, $text *}
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
  <div class="card pt-2 pb-1 text-center dashcard">
    <div class="card-body">
      <h3 class="card-title">
        {include "bits/icon.tpl" i=$icon}
      </h3>
      <a href="{$link}" class="stretched-link">
        {$text}
      </a>
    </div>
  </div>
</div>
