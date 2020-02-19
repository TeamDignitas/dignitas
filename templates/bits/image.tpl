{$condition=$condition|default:$obj->fileExtension}
{$imgClass=$imgClass|default:''}
{$spanClass=$spanClass|default:''}


{if $obj->fileExtension && $condition}
  <span class="{$spanClass} person-photo">
    {$sz=$obj->getFileSize($geometry)}
    <img
      src="{$obj->getFileUrl($geometry)}"
      class="{$imgClass}"
      {if $sz.width}width="{$sz.width}"{/if}
      {if $sz.height}height="{$sz.height}"{/if}>
  </span>
{/if}
