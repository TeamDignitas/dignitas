{$imgClass=$imgClass|default:''}
{$spanClass=$spanClass|default:''}
{$link=$link|default:false} {* whether to link to full-size image *}

{if $obj->fileExtension}

  {* prepare the <img> tag *}
  {$sz=$obj->getFileSize($geometry)}
  {capture "imgTag"}
    <img
      src="{$obj->getFileUrl($geometry)}"
      class="{$imgClass}"
      {if $sz.width}width="{$sz.width}"{/if}
      {if $sz.height}height="{$sz.height}"{/if}>
  {/capture}

  {* wrap the <img> tag in an <a> tag or a <span> tag *}
  {if $link}
    <a href="{$obj->getFileUrl('full')}" class="{$spanClass}">
      {$smarty.capture.imgTag}
    </a>
  {else}
    <span class="{$spanClass}">
      {$smarty.capture.imgTag}
    </span>
  {/if}

{/if}
