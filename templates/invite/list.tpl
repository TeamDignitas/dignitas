{extends "layout.tpl"}

{block "title"}{cap}{t}title-invites{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-invites{/t}{/cap} ({$invites|count})</h1>

    {if Config::ALLOW_INVITES}
      <div class="mb-5">
        <a href="{Router::link('invite/add')}" class="btn btn-sm btn-primary col-12 col-md-3">
          {t}link-add-invite{/t}
        </a>
      </div>
    {/if}

    <div class="table-responsive">
      <table class="table dtable text-nowrap">

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
              <td>
                {include "bits/userLink.tpl" u=$i->getSender()}
              </td>
              <td>
                {$i->email}
              </td>
              <td>
                {include "bits/userLink.tpl" u=$i->getReceiver()}
              </td>
            </tr>
          {/foreach}
        </tbody>

      </table>
    </div>
  </div>
{/block}
