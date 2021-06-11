{* used on the tag edit page *}
<small class="form-text">
  {t}label-frequent-colors{/t}:
  {foreach $colors as $color}
    <span
      data-value="{$color}"
      data-bs-target="{$target}"
      class="frequent-color"
      style="background: {$color}">
      &nbsp;
    </span>
  {/foreach}
</small>
