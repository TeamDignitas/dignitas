{$condition=$condition|default:$obj->imageExtension}
{$spanClass=$spanClass|default:''}
{if $condition}
  <span class="{$spanClass}">
    {$sz=Img::getThumbSize($obj, $geometry)}
    <img
      src="{Img::getThumbLink($obj, $geometry)}"
      class="{$imgClass}"
      width="{$sz.width|default:''}"
      height="{$sz.height|default:''}">
  </span>
{/if}
