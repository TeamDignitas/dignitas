{extends "layout.tpl"}

{block "title"}{cap}{t}title-invites{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-invites{/t}{/cap} ({$invites|count})</h1>

    {if Config::ALLOW_INVITES}
      <div class="mb-5">
        <a href="{Router::link('invite/add')}" class="btn btn-sm btn-primary col-sm-12 col-md-3">
          <i class="icon icon-plus"></i>
          {t}link-add-invite{/t}
        </a>
      </div>
    {/if}

    <table class="table table-hover">
      <thead>
        <tr class="small">
          <th class="border-0">{t}label-invite-from{/t}</th>
          <th class="border-0">{t}label-invite-to{/t}</th>
          <th class="border-0">{t}label-invite-accepted-by{/t}</th>
        </tr>
      </thead>
      <tbody>
        {foreach $invites as $i}
          <tr class="small">
            <td>{include "bits/userLink.tpl" u=$i->getSender()}</td>
            <td>{$i->email}</td>
            <td>{include "bits/userLink.tpl" u=$i->getReceiver()}</td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
{/block}
