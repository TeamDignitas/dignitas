{$condition=$condition|default:$obj->imageExtension}
{$spanClass=$spanClass|default:''}
{if $condition}
  <span class="{$spanClass}">
    {$sz=Img::getThumbSize($obj, $size)}
    <img
      src="{Img::getThumbLink($obj, $size)}"
      class="{$imgClass}"
      width="{$sz.width}"
      height="{$sz.height}">
  </span>
{/if}
