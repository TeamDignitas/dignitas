{* "view source" button for objects that have Markdown fields *}
{* mandatory argument: $obj *}

<a
  href="{Router::link('aggregate/viewMarkdown')}/{$obj->getObjectType()}/{$obj->id}"
  class="dropdown-item">

  {include "bits/icon.tpl" i=code}
  {t}link-view-markdown{/t}
</a>
