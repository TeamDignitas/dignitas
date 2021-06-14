{* used on the tag edit page *}
<div class="form-text">
  {t}label-frequent-colors{/t}:
  {foreach $colors as $color}
    <span
      data-value="{$color}"
      data-target="{$target}"
      class="frequent-color"
      style="background: {$color}">
      &nbsp;
    </span>
  {/foreach}
</div>
