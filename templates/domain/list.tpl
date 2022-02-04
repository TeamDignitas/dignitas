{extends "layout.tpl"}

{block "title"}{cap}{t}title-domains{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-domains{/t}{/cap}</h1>

    <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('domain/edit')}">
      {t}link-add-domain{/t}
    </a>

    {if count($domains)}
      <div class="table-responsive">
        <table class="table dtable text-nowrap">

          <thead>
            <tr>
              <th>{t}label-name{/t}</th>
              <th>{t}label-icon{/t}</th>
              <th>{t}label-display-value{/t}</th>
              <th>{t}actions{/t}</th>
            </tr>
          </thead>

          <tbody>
            {foreach $domains as $d}
              <tr>
                <td>
                  {$d->name|esc}
                </td>
                <td>
                  {include "bits/image.tpl"
                    obj=$d
                    geometry=Config::THUMB_DOMAIN}
                </td>
                <td>
                  {$d->displayValue|esc}
                </td>
                <td>
                  <a
                    href="{$d->getEditUrl()}"
                    class="btn btn-sm"
                    title="{t}link-edit{/t}">
                    {include "bits/icon.tpl" i=mode_edit}
                  </a>
                </td>
              </tr>
            {/foreach}
          </tbody>

        </table>
      </div>
    {/if}
  </div>
{/block}
