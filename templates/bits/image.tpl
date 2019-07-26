{$condition=$condition|default:$obj->fileExtension}
{$spanClass=$spanClass|default:''}
{if $condition}
  <span class="{$spanClass}">
    {$sz=$obj->getFileSize($geometry)}
    <img
      src="{$obj->getFileUrl($geometry)}"
      class="{$imgClass}"
      {if $sz.width}width="{$sz.width}"{/if}
      {if $sz.height}height="{$sz.height}"{/if}>
  </span>
{/if}
