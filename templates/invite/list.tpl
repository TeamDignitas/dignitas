{extends "layout.tpl"}

{block "title"}{cap}{t}title-invites{/t}{/cap}{/block}

{block "content"}

  <h3>{cap}{t}title-invites{/t}{/cap} ({$invites|count})</h3>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>{t}label-invite-from{/t}</th>
        <th>{t}label-invite-to{/t}</th>
        <th>{t}label-invite-accepted-by{/t}</th>
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
    <div>
      <a href="{Router::link('invite/add')}" class="btn btn-secondary">
        <i class="icon icon-plus"></i>
        {t}link-add-invite{/t}
      </a>
    </div>
  {/if}

{/block}
