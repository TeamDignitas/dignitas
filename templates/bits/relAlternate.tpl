{foreach Router::getRelAlternates() as $locale => $pair}
  <link rel="alternate" hreflang="{$pair[0]}" href="{$pair[1]}">
{/foreach}
