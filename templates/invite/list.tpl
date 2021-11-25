{extends "layout.tpl"}

{block "title"}{cap}{t}title-invites{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-invites{/t}{/cap} ({$invites|count})</h1>

    {if Config::ALLOW_INVITES}
      <div class="mb-5">
        <a href="{Router::link('invite/add')}" class="btn btn-sm btn-primary col-12 col-md-3">
          {include "bits/icon.tpl" i=add_circle}
          {t}link-add-invite{/t}
        </a>
      </div>
    {/if}

    <div class="gtable container">
      <div class="row gtable-header">
        <div class="col-3 col-sm-4">{t}label-invite-from{/t}</div>
        <div class="col-6 col-sm-4">{t}label-invite-to{/t}</div>
        <div class="col-3 col-sm-4">{t}label-invite-accepted-by{/t}</div>
      </div>

      {foreach $invites as $i}
        <div class="row gtable-row">
          <div class="col-3 col-sm-4">
            {include "bits/userLink.tpl" u=$i->getSender()}
          </div>
          <div class="col-6 col-sm-4">
            {$i->email}
          </div>
          <div class="col-3 col-sm-4">
            {include "bits/userLink.tpl" u=$i->getReceiver()}
          </div>
        </div>
      {/foreach}
    </div>
  </div>
{/block}
