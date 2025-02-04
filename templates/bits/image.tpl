{$imgClass=$imgClass|default:''}
{$placeholder=$placeholder|default:false}
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

{elseif $placeholder}

  <div class="img-placeholder">
    {if $obj->getObjectType() == Proto::TYPE_ENTITY}
      {if $obj->isPerson()}
        {include "bits/icon.tpl" i=person}
      {else}
        {include "bits/icon.tpl" i=groups}
      {/if}
    {/if}
    {** No placeholder for objects other than entities. **}
  </div>

{/if}
