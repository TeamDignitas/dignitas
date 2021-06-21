{extends "layout.tpl"}

{block "title"}{cap}{t}title-domains{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-domains{/t}{/cap}</h1>

    <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('domain/edit')}">
      {include "bits/icon.tpl" i=add_circle}
      {t}link-add-domain{/t}
    </a>

    {if count($domains)}
      <table class="table table-hover mt-5 mb-4">
        <thead>
          <tr class="small">
            <th>{t}label-icon{/t}</th>
            <th>{t}label-name{/t}</th>
            <th>{t}label-display-value{/t}</th>
            <th>{t}actions{/t}</th>
          </tr>
        </thead>
        <tbody>
          {foreach $domains as $d}
            <tr class="small">
              <td>
                {include "bits/image.tpl"
                  obj=$d
                  geometry=Config::THUMB_DOMAIN}
              </td>
              <td class="align-middle">{$d->name|escape}</td>
              <td class="align-middle">{$d->displayValue|escape}</td>
              <td>
                <a
                  href="{$d->getEditUrl()}"
                  class="btn"
                  title="{t}link-edit{/t}">
                  {include "bits/icon.tpl" i=mode_edit}
                </a>
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    {/if}
  </div>
{/block}
