{foreach Router::getRelAlternate() as $lang => $url}
  <link rel="alternate" hreflang="{$lang}" href="{$url}">
{/foreach}
