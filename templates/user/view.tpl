{extends "layout.tpl"}

{block "title"}{t}user{/t} {$user}{/block}

{block "content"}
  <h3>{t}user{/t} {$user}</h3>

  <dl>
    <dd>{t}reputation{/t}</dd>
    <dt>{$user->reputation}</dt>
    <dd>{t}statements{/t}</dd>
    <dt>{$statements}</dt>
    <dd>{t}answers{/t}</dd>
    <dt>{$answers}</dt>
    <dd>{t}member since{/t}</dd>
    <dt>{$user->createDate|lt}</dt>
  </dl>
{/block}
