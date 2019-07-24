{$condition=$condition|default:$obj->fileExtension}
{$spanClass=$spanClass|default:''}
{if $condition}
  <span class="{$spanClass}">
    {$sz=$obj->getFileSize($geometry)}
    <img
      src="{$obj->getFileUrl($geometry)}"
      class="{$imgClass}"
      width="{$sz.width|default:''}"
      height="{$sz.height|default:''}">
  </span>
{/if}
