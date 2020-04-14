{extends "layout.tpl"}

{block "title"}{cap}{t}title-invites{/t}{/cap}{/block}

{block "content"}

  <h1 class="mb-5">{cap}{t}title-invites{/t}{/cap} ({$invites|count})</h1>

  <table class="table table-hover">
    <thead>
      <tr>
        <th class="border-0">{t}label-invite-from{/t}</th>
        <th class="border-0">{t}label-invite-to{/t}</th>
        <th class="border-0">{t}label-invite-accepted-by{/t}</th>
      </tr>
    </thead>
    <tbody>
      {foreach $invites as $i}
        <tr>
          <td>{include "bits/userLink.tpl" u=$i->getSender()}</td>
          <td>{$i->email}</td>
          <td>{include "bits/userLink.tpl" u=$i->getReceiver()}</td>
        </tr>
      {/foreach}
    </tbody>
  </table>

  {if Config::ALLOW_INVITES}
    <div class="mt-5">
      <a href="{Router::link('invite/add')}" class="btn btn-sm btn-outline-primary">
        <i class="icon icon-plus"></i>
        {t}link-add-invite{/t}
      </a>
    </div>
  {/if}

{/block}
